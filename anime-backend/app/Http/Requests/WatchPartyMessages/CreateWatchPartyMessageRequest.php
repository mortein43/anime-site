<?php

namespace AnimeSite\Http\Requests\WatchPartyMessages;

use Illuminate\Foundation\Http\FormRequest;

class CreateWatchPartyMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
