<?php

namespace AnimeSite\DTOs\Animes;
use AnimeSite\DTOs\BaseDTO;
use AnimeSite\Enums\AnimeRelateType;
use AnimeSite\Enums\ApiSourceName;
use AnimeSite\Enums\AttachmentType;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\Status;
use AnimeSite\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
class AnimeStoreDTO extends BaseDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly Kind $kind,
        public readonly Status $status,
        public readonly ?string $studioId = null,
        public readonly ?string $poster = null,
        public readonly ?string $backdrop = null,
        public readonly ?string $image_name = null,
        public readonly array|Collection|null $countries = null,
        public readonly array|Collection|null $aliases = null,
        public readonly ?string $firstAirDate = null,
        public readonly ?string $lastAirDate = null,
        public readonly ?int $duration = null,
        public readonly ?float $imdbScore = null,
        public readonly bool $isPublished = true,
        public readonly array|Collection|null $attachments = null,
        public readonly array|Collection|null $related = null,
        public readonly array|Collection|null $similars = null,
        public readonly array|Collection|null $apiSources = null,
        public readonly ?array $tagIds = null,
        public readonly ?array $personIds = null,
        public readonly ?string $slug = null,
        public readonly ?string $metaTitle = null,
        public readonly ?string $metaDescription = null,
        public readonly ?string $metaImage = null,
    ) {
    }

    public static function fields(): array
    {
        return [
            'name',
            'description',
            'kind',
            'status',
            'studio_id' => 'studioId',
            'poster',
            'backdrop',
            'image_name',
            'countries',
            'aliases',
            'first_air_date' => 'firstAirDate',
            'last_air_date' => 'lastAirDate',
            'duration',
            'imdb_score' => 'imdbScore',
            'is_published' => 'isPublished',
            'attachments',
            'related',
            'similars',
            'api_sources' => 'apiSources',
            'tag_ids' => 'tagIds',
            'person_ids' => 'personIds',
            'slug',
            'meta_title' => 'metaTitle',
            'meta_description' => 'metaDescription',
            'meta_image' => 'metaImage',
        ];
    }

    public static function fromRequest(Request $request): static
    {
        $countries = self::processJsonArray($request->input('countries', []));
        $aliases = self::processJsonArray($request->input('aliases', []));
        $attachments = self::processAttachments($request->input('attachments', []));
        $related = self::processRelatedAnimes($request->input('related', []));
        $similars = self::processJsonArray($request->input('similars', []));
        $apiSources = self::processApiSources($request->input('api_sources', []));

        $tagIds = $request->has('tag_ids')
            ? (is_array($request->input('tag_ids')) ? $request->input('tag_ids') : explode(',', $request->input('tag_ids')))
            : null;

        $personIds = $request->has('person_ids')
            ? (is_array($request->input('person_ids')) ? $request->input('person_ids') : json_decode($request->input('person_ids'), true))
            : null;

        $slug = $request->input('slug') ?? Anime::generateSlug($request->input('name'));

        return new static(
            name: $request->input('name'),
            description: $request->input('description'),
            kind: Kind::from($request->input('kind')),
            status: Status::from($request->input('status')),
            studioId: $request->input('studio_id'),
            poster: $request->input('poster'),
            backdrop: $request->input('backdrop'),
            image_name: $request->input('image_name'),
            countries: $countries,
            aliases: $aliases,
            firstAirDate: $request->input('first_air_date'),
            lastAirDate: $request->input('last_air_date'),
            duration: $request->has('duration') ? (int) $request->input('duration') : null,
            imdbScore: $request->has('imdb_score') ? (float) $request->input('imdb_score') : null,
            isPublished: $request->boolean('is_published', true),
            attachments: $attachments,
            related: $related,
            similars: $similars,
            apiSources: $apiSources,
            tagIds: $tagIds,
            personIds: $personIds,
            slug: $slug,
            metaTitle: $request->input('meta_title'),
            metaDescription: $request->input('meta_description'),
            metaImage: $request->input('meta_image'),
        );
    }

    private static function processJsonArray($input): array
    {
        return is_string($input) ? json_decode($input, true) ?? [] : (is_array($input) ? $input : []);
    }

    private static function processAttachments($input): array
    {
        $attachments = self::processJsonArray($input);

        foreach ($attachments as $key => $attachment) {
            if (isset($attachment['type']) && is_string($attachment['type'])) {
                try {
                    $attachments[$key]['type'] = AttachmentType::from($attachment['type'])->value;
                } catch (\ValueError) {
                    $attachments[$key]['type'] = AttachmentType::TRAILER->value;
                }
            }

            if (!isset($attachment['duration']) || !is_numeric($attachment['duration'])) {
                $attachments[$key]['duration'] = 0;
            }
        }

        return $attachments;
    }

    private static function processRelatedAnimes($input): array
    {
        $relatedAnimes = self::processJsonArray($input);

        foreach ($relatedAnimes as $key => $related) {
            if (isset($related['type']) && is_string($related['type'])) {
                try {
                    $relatedAnimes[$key]['type'] = AnimeRelateType::from($related['type'])->value;
                } catch (\ValueError) {
                    $relatedAnimes[$key]['type'] = AnimeRelateType::OTHER->value;
                }
            }
        }

        return $relatedAnimes;
    }

    private static function processApiSources($input): array
    {
        $apiSources = self::processJsonArray($input);

        foreach ($apiSources as $key => $source) {
            if (isset($source['source']) && is_string($source['source'])) {
                try {
                    $apiSources[$key]['source'] = ApiSourceName::from($source['source'])->value;
                } catch (\ValueError) {
                    $apiSources[$key]['source'] = ApiSourceName::TMDB->value;
                }
            }
        }

        return $apiSources;
    }
}

