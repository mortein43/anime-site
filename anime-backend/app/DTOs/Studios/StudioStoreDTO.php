<?php

namespace AnimeSite\DTOs\Studios;
use AnimeSite\DTOs\BaseDTO;
use AnimeSite\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
class StudioStoreDTO extends BaseDTO
{
    /**
     * Create a new StudioStoreDTO instance.
     *
     * @param string $name Studio name
     * @param string $description Studio description
     * @param string|null $image Studio logo URL
     * @param string|null $slug Studio slug
     * @param string|null $metaTitle SEO meta title
     * @param string|null $metaDescription SEO meta description
     * @param string|null $metaImage SEO meta image
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly ?string $image = null,
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
            'image',
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

        // Generate slug if not provided
        $slug = $request->input('slug');
        if (!$slug) {
            $slug = Studio::generateSlug($request->input('name'));
        }

        return new static(
            name: $request->input('name'),
            description: $request->input('description'),
            image: $request->input('image'),
            slug: $slug,
            metaTitle: $request->input('meta_title', Studio::makeMetaTitle($request->input('name'))),
            metaDescription: $request->input('meta_description'),
            metaImage: $request->input('meta_image'),
        );
    }
}
