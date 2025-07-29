<?php

namespace AnimeSite\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))
            ? RateLimiter::clear($this->throttleKey())
            : $this->handleFailedAttempt();
    }

    /**
     * Handle a failed authentication attempt.
     *
     * @throws ValidationException
     */
    private function handleFailedAttempt(): void
    {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        RateLimiter::tooManyAttempts($this->throttleKey(), 5)
            ? $this->handleRateLimiting()
            : null;
    }

    /**
     * Handle rate limiting and throw appropriate exception.
     *
     * @throws ValidationException
     */
    private function handleRateLimiting(): void
    {
        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }

    /**
     * Get the body parameters for the request.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'email' => [
                'description' => 'Електронна пошта користувача.',
                'example' => 'user@example.com',
            ],
            'password' => [
                'description' => 'Пароль користувача.',
                'example' => 'password123',
            ],
            'remember' => [
                'description' => 'Чи запам\'ятати користувача (необов\'язково).',
                'example' => true,
            ],
        ];
    }
}
