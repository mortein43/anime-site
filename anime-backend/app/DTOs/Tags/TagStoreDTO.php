<?php

namespace AnimeSite\DTOs\Tags;

use AnimeSite\DTOs\BaseDTO;
use AnimeSite\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagStoreDTO extends BaseDTO
{
    /**
     * Create a new TagStoreDTO instance.
     *
     * @param string $name Tag name
     * @param string $description Tag description
     * @param bool $isGenre Whether the tag is a genre
     * @param string|null $image Tag image URL
     * @param array|Collection $aliases Alternative names or abbreviations
     * @param string|null $slug Tag slug
     * @param string|null $metaTitle SEO meta title
     * @param string|null $metaDescription SEO meta description
     * @param string|null $metaImage SEO meta image
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly bool $isGenre = false,
        public readonly ?string $image = null,
        public readonly array|Collection $aliases = [],
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
            'is_genre' => 'isGenre',
            'image',
            'aliases',
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
        // Process aliases
        $aliases = self::processArrayInput($request, 'aliases');

        // Generate slug if not provided
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Tag::generateSlug($request->input('name'));
        }

        return new static(
            name: $request->input('name'),
            description: $request->input('description'),
            isGenre: $request->boolean('is_genre', false),
            image: $request->input('image'),
            aliases: $aliases ?? [],
            slug: $slug,
            metaTitle: $request->input('meta_title'),
            metaDescription: $request->input('meta_description'),
            metaImage: $request->input('meta_image'),
        );
    }

    /**
     * Process array input from request.
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
            // Try JSON decode first
            $decoded = json_decode($input, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            // Fallback to CSV
            return array_filter(array_map('trim', explode(',', $input)));
        }

        return is_array($input) ? $input : [];
    }
}
