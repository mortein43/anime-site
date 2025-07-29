<?php

namespace AnimeSite\Http\Requests\Users;

use AnimeSite\Models\User;
use AnimeSite\Models\Scopes\BannedScope;
use Illuminate\Foundation\Http\FormRequest;

class UserBanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $action = $this->route()->getActionMethod();

        // Використовуємо різні політики для блокування та розблокування
        if ($action === 'ban') {
            $user = $this->route('user');
            return $this->user()->can('ban', $user);
        } else {
            // Для розблокування використовуємо спеціальний параметр id
            $id = $this->route('id');
            $user = User::withoutGlobalScope('AnimeSite\Models\Scopes\BannedScope')->find($id);

            if (!$user) {
                return false;
            }

            // Для розблокування використовуємо ту ж політику, що й для оновлення
            return $this->user()->can('update', $user);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get the body parameters for the request.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'reason' => [
                'description' => 'Причина блокування користувача (необов’язково).',
                'example' => 'Порушення правил користування сервісом.',
            ],
        ];
    }
}
