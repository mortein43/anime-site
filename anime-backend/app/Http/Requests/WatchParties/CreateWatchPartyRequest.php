<?php

namespace AnimeSite\Http\Requests\WatchParties;

use Illuminate\Foundation\Http\FormRequest;

class CreateWatchPartyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'episode_id' => ['required', 'ulid', 'exists:episodes,id'],
            'is_private' => ['sometimes', 'boolean'],
            'password' => ['nullable', 'string', 'max:255'],
            'max_viewers' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
