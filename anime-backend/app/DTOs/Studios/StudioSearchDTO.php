<?php

namespace AnimeSite\DTOs\Studios;
use AnimeSite\DTOs\BaseDTO;
use Illuminate\Http\Request;
class StudioSearchDTO extends BaseDTO
{
    /**
     * Create a new StudioSearchDTO instance.
     *
     * @param string $query Search query
     * @param int $page Current page number
     * @param int $perPage Number of items per page
     * @param string|null $sort Field to sort by
     * @param string $direction Sort direction (asc or desc)
     * @param bool|null $hasAnimes Filter studios that have animes
     */
    public function __construct(
        public readonly string $query,
        public readonly int $page = 1,
        public readonly int $perPage = 15,
        public readonly ?string $sort = 'created_at',
        public readonly string $direction = 'desc',
        public readonly ?bool $hasAnimes = null,
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
            'query',
            'page',
            'per_page' => 'perPage',
            'sort',
            'direction',
            'has_animes' => 'hasAnimes',
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
        return new static(
            query: $request->query,
            page: (int) $request->input('page', 1),
            perPage: (int) $request->input('per_page', 15),
            sort: $request->input('sort', 'created_at'),
            direction: $request->input('direction', 'desc'),
            hasAnimes: $request->has('has_animes') ? (bool) $request->input('has_animes') : null,
        );
    }
}
