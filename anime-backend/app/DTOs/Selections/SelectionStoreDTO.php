<?php

namespace AnimeSite\DTOs\Selections;

use AnimeSite\DTOs\BaseDTO;
use AnimeSite\Models\Selection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SelectionStoreDTO extends BaseDTO
{
    /**
     * Create a new SelectionStoreDTO instance.
     *
     * @param string $name Selection name
     * @param string $description Selection description
     * @param string $userId User ID who created the selection
     * @param bool $isPublished Whether the selection is published
     * @param array|Collection $animeIds Anime IDs to include in the selection
     * @param array|Collection $personIds Person IDs to include in the selection
     * @param array|Collection $episodeIds Episode IDs to include in the selection
     * @param string|null $slug Selection slug
     * @param string|null $metaTitle SEO meta title
     * @param string|null $metaDescription SEO meta description
     * @param string|null $metaImage SEO meta image
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $userId,
        public readonly bool $isPublished = true,
        public readonly array|Collection $animeIds = [],
        public readonly array|Collection $personIds = [],
        public readonly array|Collection $episodeIds = [],
        public readonly ?string $slug = null,
        public readonly ?string $metaTitle = null,
        public readonly ?string $metaDescription = null,
        public readonly ?string $metaImage = null,
    ) {
    }

    /**
     * Get the fields that should be used for the DTO.
     *
     * @return array
     */
    public static function fields(): array
    {
        return [
            'name',
            'description',
            'user_id' => 'userId',
            'is_published' => 'isPublished',
            'anime_ids' => 'animeIds',
            'person_ids' => 'personIds',
            'episode_ids' => 'episodeIds',
            'slug',
            'meta_title' => 'metaTitle',
            'meta_description' => 'metaDescription',
            'meta_image' => 'metaImage',
        ];
    }

    /**
     * Create a new DTO instance from request.
     *
     * @param Request $request
     * @return static
     */
    public static function fromRequest(Request $request): static
    {
        // Process animes IDs
        $aimeIds = self::processArrayInput($request, 'anime_ids');

        // Process person IDs
        $personIds = self::processArrayInput($request, 'person_ids');

        // Process episode IDs
        $episodeIds = self::processArrayInput($request, 'episode_ids');

        // Generate slug if not provided
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Selection::generateSlug($request->input('name'));
        }

        return new static(
            name: $request->input('name'),
            description: $request->input('description'),
            userId: $request->input('user_id', $request->user()->id),
            isPublished: $request->boolean('is_published', true),
            animeIds: $animeIds ?? [],
            personIds: $personIds ?? [],
            episodeIds: $episodeIds ?? [],
            slug: $slug,
            metaTitle: $request->input('meta_title'),
            metaDescription: $request->input('meta_description'),
            metaImage: $request->input('meta_image'),
        );
    }

    /**
     * Process array input from request
     *
     * @param Request $request
     * @param string $key
     * @return array|null
     */
    private static function processArrayInput(Request $request, string $key): ?array
    {
        if (!$request->has($key)) {
            return [];
        }

        $input = $request->input($key);
        if (is_string($input)) {
            return explode(',', $input);
        }

        return $input;
    }
}
