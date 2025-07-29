<?php

namespace AnimeSite\Http\Requests\Animes;
use AnimeSite\Enums\ApiSourceName;
use AnimeSite\Enums\AttachmentType;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\Status;
use AnimeSite\Models\Anime;
use AnimeSite\Rules\FileOrString;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
class AnimeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Anime::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:248'],
            'description' => ['required', 'string'],
            'slug' => ['nullable', 'string', 'max:128', 'unique:animes,slug'],
            'kind' => ['required', new Enum(Kind::class)],
            'status' => ['required', new Enum(Status::class)],
            'studio_id' => ['required', 'string', 'exists:studios,id'],
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
            'episodes_count' => ['nullable', 'integer', 'min:1'],
            'imdb_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'attachments' => ['nullable', 'array'],
            'attachments.*.type' => ['required', 'string', new Enum(AttachmentType::class)],
            'attachments.*.url' => ['required', 'string', 'url', 'max:2048'],
            'attachments.*.title' => ['required', 'string', 'max:255'],
            'attachments.*.duration' => ['required', 'integer', 'min:1'],
            'related' => ['nullable', 'array'],
            'related.*' => ['string', 'exists:animes,id'],
            'similars' => ['nullable', 'array'],
            'similars.*' => ['string', 'exists:animes,id'],
            'api_sources' => ['nullable', 'array'],
            'api_sources.*.source' => ['required', 'string', new Enum(ApiSourceName::class)],
            'api_sources.*.id' => ['required', 'string', 'max:255'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['string', 'exists:tags,id'],
            'person_ids' => ['nullable', 'array'],
            'person_ids.*' => ['string', 'exists:people,id'],
            'is_published' => ['sometimes', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:128'],
            'meta_description' => ['nullable', 'string', 'max:376'],
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

    public function bodyParameters(): array
    {
        return [
            'name' => ['description' => 'Назва аніме.', 'example' => 'Naruto'],
            'description' => ['description' => 'Опис аніме.', 'example' => 'Історія про хлопця-ніндзя...'],
            'slug' => ['description' => 'Унікальний слаг для URL.', 'example' => 'naruto'],
            'kind' => ['description' => 'Тип аніме (tv_series, ova, ona і т.д.)', 'example' => 'tv_series'],
            'status' => ['description' => 'Статус релізу (released, ongoing, тощо).', 'example' => 'released'],
            'studio_id' => ['description' => 'ID студії-виробника.', 'example' => '01HN5PXMEH6SDMF0KAVSW1DYTY'],
            'poster' => ['description' => 'Постер аніме (файл або URL).', 'example' => 'https://example.com/poster.jpg'],
            'backdrop' => ['description' => 'Фон аніме (файл або URL).', 'example' => 'https://example.com/backdrop.jpg'],
            'image_name' => ['description' => 'Зображення з назвою (файл або URL).', 'example' => 'https://example.com/title.jpg'],
            'countries' => ['description' => 'Країни виробництва (ISO коди).', 'example' => ['JP']],
            'aliases' => ['description' => 'Альтернативні назви.', 'example' => ['ナルト', 'Naruto']],
            'first_air_date' => ['description' => 'Дата першого показу.', 'example' => '2002-10-03'],
            'last_air_date' => ['description' => 'Дата останнього показу.', 'example' => '2007-02-08'],
            'duration' => ['description' => 'Тривалість серії в хвилинах.', 'example' => 24],
            'episodes_count' => ['description' => 'Кількість епізодів.', 'example' => 220],
            'imdb_score' => ['description' => 'Оцінка IMDb.', 'example' => 8.3],
            'attachments' => ['description' => 'Відеоплеєри та трейлери.', 'example' => [['type' => 'TRAILER', 'url' => 'https://youtube.com/xyz', 'title' => 'Трейлер', 'duration' => 120]]],
            'related' => ['description' => 'ID повʼязаних аніме.', 'example' => ['01XYZ...']],
            'similars' => ['description' => 'ID подібних аніме.', 'example' => ['01ABC...']],
            'api_sources' => ['description' => 'API джерела.', 'example' => [['source' => 'ANILIST', 'id' => '12345']]],
            'tag_ids' => ['description' => 'ID тегів.', 'example' => ['01T1...', '01T2...']],
            'person_ids' => ['description' => 'ID персон.', 'example' => ['01P1...', '01P2...']],
            'is_published' => ['description' => 'Чи опубліковано.', 'example' => true],
            'meta_title' => ['description' => 'SEO заголовок.', 'example' => 'Naruto - дивитися онлайн'],
            'meta_description' => ['description' => 'SEO опис.', 'example' => 'Аніме Naruto онлайн у HD якості.'],
            'meta_image' => ['description' => 'SEO зображення.', 'example' => 'https://example.com/meta.jpg'],
        ];
    }
}

