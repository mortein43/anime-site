<?php

namespace AnimeSite\Http\Requests\Animes;
use Illuminate\Foundation\Http\FormRequest;
class AnimeDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $anime = $this->route('anime');

        return $this->user()->can('delete', $anime);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get the body parameters for the request.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [];
    }

    /**
     * Get the URL parameters for the request.
     *
     * @return array
     */
    public function urlParameters()
    {
        return [
            'anime' => [
                'description' => 'Slug аніме, який потрібно видалити.',
                'example' => 'interstellar-abc123',
            ],
        ];
    }
}
