<?php

namespace AnimeSite\DTOs\Tags;

use AnimeSite\DTOs\BaseDTO;
use Illuminate\Http\Request;

class TagIndexDTO extends BaseDTO
{
    /**
     * Create a new TagIndexDTO instance.
     *
     * @param string|null $query Search query
     * @param int $page Current page number
     * @param int $perPage Number of items per page
     * @param string|null $sort Field to sort by
     * @param string $direction Sort direction (asc or desc)
     * @param bool|null $isGenre Filter by genre status
     * @param bool|null $hasAnimes Filter tags that have animes
     * @param bool|null $hasSelections Filter tags that have selections
     * @param bool|null $hasPeople Filter tags that have persons
     * @param array|null $animeIds Filter by anime IDs
     * @param array|null $selectionIds Filter by selection IDs
     * @param array|null $peopleIds Filter by person IDs
     */
    public function __construct(
        public readonly ?string $query = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15,
        public readonly ?string $sort = 'created_at',
        public readonly string $direction = 'desc',
        public readonly ?bool $isGenre = null,
        public readonly ?bool $hasAnimes = null,
        public readonly ?bool $hasSelections = null,
        public readonly ?bool $hasPeople = null,
        public readonly ?array $animeIds = null,
        public readonly ?array $selectionIds = null,
        public readonly ?array $peopleIds = null,
    ) {
    }

    public static function fields(): array
    {
        return [
            'q' => 'query',
            'page',
            'per_page' => 'perPage',
            'sort',
            'direction',
            'is_genre' => 'isGenre',
            'has_animes' => 'hasAnimes',
            'anime_ids' => 'animeIds',
            'has_selections' => 'hasSelections',
            'has_people' => 'hasPeople',
            'selection_ids' => 'selectionIds',
            'people_ids' => 'peopleIds',
        ];
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            query: $request->input('q'),
            page: (int) $request->input('page', 1),
            perPage: (int) $request->input('per_page', 15),
            sort: $request->input('sort', 'created_at'),
            direction: $request->input('direction', 'desc'),
            isGenre: $request->has('is_genre') ? (bool) $request->input('is_genre') : null,
            hasAnimes: $request->has('has_animes') ? (bool) $request->input('has_animes') : null,
            hasSelections: $request->has('has_selections') ? (bool) $request->input('has_selections') : null,
            hasPeople: $request->has('has_people') ? (bool) $request->input('has_people') : null,
            animeIds: self::processArrayInput($request, 'anime_ids'),
            selectionIds: self::processArrayInput($request, 'selection_ids'),
            peopleIds: self::processArrayInput($request, 'people_ids'),
        );
    }

    private static function processArrayInput(Request $request, string $key): ?array
    {
        if (!$request->has($key)) {
            return null;
        }

        $input = $request->input($key);
        if (is_string($input)) {
            return array_filter(explode(',', $input));
        }

        return is_array($input) ? $input : null;
    }
}
