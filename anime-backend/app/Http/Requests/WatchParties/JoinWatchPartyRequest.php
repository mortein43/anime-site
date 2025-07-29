<?php

namespace AnimeSite\Http\Requests\WatchParties;

use Illuminate\Foundation\Http\FormRequest;

class JoinWatchPartyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'exists:watch_parties,slug'],
            'name' => ['required', 'string', 'max:255'], // 🔹 Обов’язкова назва кімнати
            'password' => ['nullable', 'string', 'size:6'],
        ];
    }
}
