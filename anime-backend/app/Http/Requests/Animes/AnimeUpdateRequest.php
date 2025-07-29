<?php

namespace AnimeSite\Http\Requests\Animes;
use AnimeSite\Enums\AnimeRelateType;
use AnimeSite\Enums\ApiSourceName;
use AnimeSite\Enums\AttachmentType;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\Status;
use AnimeSite\Rules\FileOrString;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AnimeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $anime = $this->route('anime');
        return $this->user()->can('update', $anime);
    }

    public function rules(): array
    {
        $anime = $this->route('anime');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'kind' => ['sometimes', new Enum(Kind::class)],
            'status' => ['sometimes', new Enum(Status::class)],
            'studio_id' => ['nullable', 'string', 'exists:studios,id'],
            'poster' => ['nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 10240)],
            'backdrop' => ['nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 10240)],
            'image_name' => ['nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 10240)],
            'countries' => ['nullable', 'array'],
            'countries.*' => ['string', 'max:2'],
            'aliases' => ['nullable', 'array'],
            'aliases.*' => ['string', 'max:255'],
            'first_air_date' => ['nullable', 'date'],
            'last_air_date' => ['nullable', 'date', 'after_or_equal:first_air_date'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'imdb_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'is_published' => ['sometimes', 'boolean'],
            'attachments' => ['nullable', 'array'],
            'attachments.*.type' => ['required', 'string', new Enum(AttachmentType::class)],
            'attachments.*.url' => ['required', 'string', 'url', 'max:2048'],
            'attachments.*.title' => ['required', 'string', 'max:255'],
            'attachments.*.duration' => ['required', 'integer', 'min:1'],
            'related' => ['nullable', 'array'],
            'related.*.anime_id' => ['required', 'string', 'exists:animes,id'],
            'related.*.type' => ['required', 'string', new Enum(AnimeRelateType::class)],
            'similars' => ['nullable', 'array'],
            'similars.*' => ['string', 'exists:animes,id'],
            'api_sources' => ['nullable', 'array'],
            'api_sources.*.source' => ['required', 'string', new Enum(ApiSourceName::class)],
            'api_sources.*.id' => ['required', 'string', 'max:255'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['string', 'exists:tags,id'],
            'person_ids' => ['nullable', 'array'],
            'person_ids.*' => ['exists:people,id'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('animes', 'slug')->ignore($anime)],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_image' => ['nullable', new FileOrString(['image/jpeg', 'image/png', 'image/webp'], 10240)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->convertJsonToArray('countries');
        $this->convertJsonToArray('aliases');
        $this->convertJsonToArray('attachments');
        $this->convertJsonToArray('related');
        $this->convertJsonToArray('similars');
        $this->convertJsonToArray('api_sources');
        $this->convertCommaSeparatedToArray('tag_ids');
    }

    private function convertJsonToArray(string $field): void
    {
        if ($this->has($field) && is_string($this->input($field))) {
            $this->merge([
                $field => json_decode($this->input($field), true) ?? []
            ]);
        }
    }

    private function convertCommaSeparatedToArray(string $field): void
    {
        if ($this->has($field) && is_string($this->input($field))) {
            $this->merge([
                $field => explode(',', $this->input($field))
            ]);
        }
    }

    public function bodyParameters()
    {
        return [
            'name' => ['description' => 'Назва аніме.', 'example' => 'Наруто'],
            'description' => ['description' => 'Опис аніме.', 'example' => 'Історія ніндзя Наруто Узумакі...'],
            'kind' => ['description' => 'Тип контенту (TV, OVA, MOVIE, тощо).', 'example' => 'TV'],
            'status' => ['description' => 'Статус аніме (RELEASED, ONGOING, тощо).', 'example' => 'RELEASED'],
            'studio_id' => ['description' => 'ID студії-виробника аніме.', 'example' => '01HN5PXMEH6SDMF0KAVSW1DYTY'],
            'poster' => ['description' => 'Постер аніме.', 'example' => 'https://example.com/poster.jpg'],
            'backdrop' => ['description' => 'Фонове зображення аніме.', 'example' => 'https://example.com/backdrop.jpg'],
            'image_name' => ['description' => 'Зображення з назвою аніме.', 'example' => 'https://example.com/title.jpg'],
            'countries' => ['description' => 'Країни-виробники (ISO-коди).', 'example' => ['JP']],
            'aliases' => ['description' => 'Альтернативні назви.', 'example' => ['Naruto', 'ナルト']],
            'first_air_date' => ['description' => 'Дата першого виходу.', 'example' => '2002-10-03'],
            'last_air_date' => ['description' => 'Дата останнього виходу.', 'example' => '2007-02-08'],
            'duration' => ['description' => 'Тривалість одного епізоду.', 'example' => 23],
            'imdb_score' => ['description' => 'Оцінка IMDb.', 'example' => 8.3],
            'is_published' => ['description' => 'Чи опубліковане аніме.', 'example' => true],
            'attachments' => [
                'description' => 'Трейлери, плеєри тощо.',
                'example' => [[
                    'type' => 'TRAILER',
                    'url' => 'https://youtube.com/watch?v=abc123',
                    'title' => 'Офіційний трейлер',
                    'duration' => 120,
                ]],
            ],
            'related' => [
                'description' => 'Пов’язані аніме.',
                'example' => [[
                    'anime_id' => '01HN5PXMEH6SDMF0KAVSW1DYTY',
                    'type' => 'PREQUEL',
                ]],
            ],
            'similars' => ['description' => 'Подібні аніме.', 'example' => ['01H...', '01G...']],
            'api_sources' => [
                'description' => 'Джерела з API.',
                'example' => [[
                    'source' => 'SHIKIMORI',
                    'id' => 'naruto',
                ]],
            ],
            'tag_ids' => ['description' => 'Теги.', 'example' => ['01H...', '01G...']],
            'person_ids' => ['description' => 'Персони, пов’язані з аніме.', 'example' => ['01H...', '01G...']],
            'slug' => ['description' => 'Слаг для URL.', 'example' => 'naruto'],
            'meta_title' => ['description' => 'SEO-заголовок.', 'example' => 'Наруто (2002) — Дивитись онлайн'],
            'meta_description' => ['description' => 'SEO-опис.', 'example' => 'Дивіться Наруто всі серії онлайн українською.'],
            'meta_image' => ['description' => 'SEO-зображення.', 'example' => 'https://example.com/meta.jpg'],
        ];
    }

    public function urlParameters()
    {
        return [
            'anime' => [
                'description' => 'Slug аніме, яке потрібно оновити.',
                'example' => 'naruto-abc123',
            ],
        ];
    }
}

