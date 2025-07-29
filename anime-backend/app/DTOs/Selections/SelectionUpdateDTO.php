<?php

namespace AnimeSite\DTOs\Selections;

use AnimeSite\DTOs\BaseDTO;
use AnimeSite\Models\Selection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SelectionUpdateDTO extends BaseDTO
{
    /**
     * Create a new SelectionUpdateDTO instance.
     *
     * @param string|null $name Selection name
     * @param string|null $description Selection description
     * @param string|null $userId User ID who created the selection
     * @param bool|null $isPublished Whether the selection is published
     * @param array|Collection|null $animeIds Anime IDs to include in the selection
     * @param array|Collection|null $personIds Person IDs to include in the selection
     * @param array|Collection|null $episodeIds Person IDs to include in the selection
     * @param string|null $slug Selection slug
     * @param string|null $metaTitle SEO meta title
     * @param string|null $metaDescription SEO meta description
     * @param string|null $metaImage SEO meta image
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?string $userId = null,
        public readonly ?bool $isPublished = null,
        public readonly array|Collection|null $animeIds = null,
        public readonly array|Collection|null $personIds = null,
        public readonly array|Collection|null $episodeIds = null,
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
        // Process anime IDs
        $animeIds = null;
        if ($request->has('anime_ids')) {
            $animeIds = self::processArrayInput($request, 'anime_ids');
        }

        // Process person IDs
        $personIds = null;
        if ($request->has('person_ids')) {
            $personIds = self::processArrayInput($request, 'person_ids');
        }

        // Process episode IDs
        $episodeIds = null;
        if ($request->has('episode_ids')) {
            $episodeIds = self::processArrayInput($request, 'episode_ids');
        }

        // Generate slug if name is provided but slug is not
        $slug = $request->input('slug');
        if (!$slug && $request->has('name')) {
            $slug = Selection::generateSlug($request->input('name'));
        }

        return new static(
            name: $request->input('name'),
            description: $request->input('description'),
            userId: $request->input('user_id'),
            isPublished: $request->has('is_published') ? $request->boolean('is_published') : null,
            animeIds: $animeIds,
            personIds: $personIds,
            episodeIds: $episodeIds,
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
            return null;
        }

        $input = $request->input($key);
        if (is_string($input)) {
            return explode(',', $input);
        }

        return $input;
    }
}
