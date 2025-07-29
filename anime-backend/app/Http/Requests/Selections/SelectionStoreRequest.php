<?php

namespace AnimeSite\Http\Requests\Selections;

use AnimeSite\Models\Selection;
use AnimeSite\Rules\FileOrString;
use Illuminate\Foundation\Http\FormRequest;

class SelectionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Selection::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'user_id' => ['sometimes', 'string', 'exists:users,id'],
            'is_published' => ['sometimes', 'boolean'],
            'anime_ids' => ['sometimes', 'array'],
            'anime_ids.*' => ['string', 'exists:animes,id'],
            'person_ids' => ['sometimes', 'array'],
            'person_ids.*' => ['string', 'exists:people,id'],
            'episode_ids' => ['sometimes', 'array'],
            'episode_ids.*' => ['string', 'exists:episodes,id'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:selections,slug'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_image' => ['nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 10240)],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Convert comma-separated values to arrays
        $this->convertCommaSeparatedToArray('anime_ids');
        $this->convertCommaSeparatedToArray('person_ids');
        $this->convertCommaSeparatedToArray('episode_ids');
    }

    /**
     * Convert comma-separated string to array
     *
     * @param  string  $field
     * @return void
     */
    private function convertCommaSeparatedToArray(string $field): void
    {
        if ($this->has($field) && is_string($this->input($field))) {
            $this->merge([
                $field => explode(',', $this->input($field))
            ]);
        }
    }

    /**
     * Get the body parameters for the request.
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'Назва підбірки.',
                'example' => 'Найкращі фільми 2023 року',
            ],
            'description' => [
                'description' => 'Опис підбірки.',
                'example' => 'Збірка найкращих фільмів, які вийшли в 2023 році...',
            ],
            'user_id' => [
                'description' => 'ID користувача, який створив підбірку (необов\'язково).',
                'example' => '01HN5PXMEH6SDMF0KAVSW1DYTY',
            ],
            'is_published' => [
                'description' => 'Чи опублікована підбірка.',
                'example' => true,
            ],
            'anime_ids' => [
                'description' => 'Масив ID аніме в підбірці.',
                'example' => ['01HN5PXMEH6SDMF0KAVSW1DYTY', '01HN5PXMEH6SDMF0KAVSW1DYTZ'],
            ],
            'person_ids' => [
                'description' => 'Масив ID персон в підбірці.',
                'example' => ['01HN5PXMEH6SDMF0KAVSW1DYTY', '01HN5PXMEH6SDMF0KAVSW1DYTZ'],
            ],
            'episode_ids' => [
                'description' => 'Масив ID епізодів в підбірці.',
                'example' => ['01HN5PXMEH6SDMF0KAVSW1DYTY', '01HN5PXMEH6SDMF0KAVSW1DYTZ'],
            ],
            'slug' => [
                'description' => 'Унікальний ідентифікатор підбірки для URL.',
                'example' => 'best-animes-2023',
            ],
            'meta_title' => [
                'description' => 'SEO заголовок.',
                'example' => 'Найкращі фільми 2023 року - Топ підбірка',
            ],
            'meta_description' => [
                'description' => 'SEO опис.',
                'example' => 'Дивіться найкращі фільми 2023 року в нашій топ-підбірці.',
            ],
            'meta_image' => [
                'description' => 'SEO зображення (файл або URL).',
                'example' => 'https://example.com/images/best-animes-2023.jpg',
            ],
        ];
    }
}
