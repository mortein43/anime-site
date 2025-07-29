<?php

namespace AnimeSite\DTOs\Ratings;

use AnimeSite\DTOs\BaseDTO;
use Illuminate\Http\Request;

class RatingIndexDTO extends BaseDTO
{
    /**
     * Create a new RatingIndexDTO instance.
     *
     * @param string|null $query Search query
     * @param int $page Current page number
     * @param int $perPage Number of items per page
     * @param string|null $sort Field to sort by
     * @param string $direction Sort direction (asc or desc)
     * @param string|null $userId Filter by user ID
     * @param string|null $animeId Filter by anime ID
     * @param int|null $minRating Filter by minimum rating
     * @param int|null $maxRating Filter by maximum rating
     * @param bool|null $hasReview Filter by presence of review
     */
    public function __construct(
        public readonly ?string $query = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15,
        public readonly ?string $sort = 'created_at',
        public readonly string $direction = 'desc',
        public readonly ?string $userId = null,
        public readonly ?string $animeId = null,
        public readonly ?int $minRating = null,
        public readonly ?int $maxRating = null,
        public readonly ?bool $hasReview = null,
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
            'q' => 'query',
            'page',
            'per_page' => 'perPage',
            'sort',
            'direction',
            'user_id' => 'userId',
            'anime_id' => 'animeId',
            'min_rating' => 'minRating',
            'max_rating' => 'maxRating',
            'has_review' => 'hasReview',
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
            query: $request->input('q'),
            page: (int) $request->input('page', 1),
            perPage: (int) $request->input('per_page', 15),
            sort: $request->input('sort', 'created_at'),
            direction: $request->input('direction', 'desc'),
            userId: $request->input('user_id'),
            animeId: $request->input('anime_id'),
            minRating: $request->has('min_rating') ? (int) $request->input('min_rating') : null,
            maxRating: $request->has('max_rating') ? (int) $request->input('max_rating') : null,
            hasReview: $request->has('has_review') ? (bool) $request->input('has_review') : null,
        );
    }
}
