<?php

namespace AnimeSite\Http\Requests\Tags;

use Illuminate\Foundation\Http\FormRequest;

class TagIndexRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'string', 'max:255'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'sort' => ['sometimes', 'string', 'in:name,created_at,taggables_count'],
            'direction' => ['sometimes', 'string', 'in:asc,desc'],
            'is_genre' => ['sometimes', 'boolean'],
            'has_taggables' => ['sometimes', 'boolean'],

            // Поліморфні фільтри
            'taggable_type' => ['sometimes', 'string', 'in:anime,selection,person'],
            'taggable_ids' => ['sometimes', 'array'],
            'taggable_ids.*' => ['sometimes', 'string', 'uuid'], // або змініть тип, якщо не UUID
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->convertCommaSeparatedToArray('taggable_ids');
    }

    /**
     * Convert comma-separated string to array.
     */
    private function convertCommaSeparatedToArray(string $field): void
    {
        if ($this->has($field) && is_string($this->input($field))) {
            $this->merge([
                $field => array_filter(explode(',', $this->input($field))),
            ]);
        }
    }

    /**
     * Get the query parameters for documentation.
     */
    public function queryParameters(): array
    {
        return [
            'q' => [
                'description' => 'Пошуковий запит для фільтрації тегів за назвою.',
                'example' => 'драма',
            ],
            'page' => [
                'description' => 'Номер сторінки для пагінації.',
                'example' => 1,
            ],
            'per_page' => [
                'description' => 'Кількість тегів на сторінку.',
                'example' => 20,
            ],
            'sort' => [
                'description' => 'Поле для сортування тегів (name, created_at, taggables_count).',
                'example' => 'taggables_count',
            ],
            'direction' => [
                'description' => 'Напрямок сортування (asc або desc).',
                'example' => 'desc',
            ],
            'is_genre' => [
                'description' => 'Фільтр для тегів, що є жанрами.',
                'example' => true,
            ],
            'has_taggables' => [
                'description' => 'Фільтр для тегів, що мають хоча б одне пов’язане джерело (anime, selection, person тощо).',
                'example' => true,
            ],
            'taggable_type' => [
                'description' => 'Тип пов’язаної моделі: anime, selection або person.',
                'example' => 'anime',
            ],
            'taggable_ids' => [
                'description' => 'ID моделей, з якими пов’язані теги. Можна передавати масивом або строкою через кому.',
                'example' => ['01HXYZANIME123', '01HXYZANIME456'],
            ],
        ];
    }
}
