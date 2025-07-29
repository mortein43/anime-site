<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class RatingQueryBuilder extends Builder
{
    /**
     * Filter by user.
     *
     * @param string $userId
     * @return self
     */
    public function forUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Filter by anime.
     *
     * @param string $animeId
     * @return self
     */
    public function forAnime(string $animeId): self
    {
        return $this->where('anime_id', $animeId);
    }

    /**
     * Filter by rating range.
     *
     * @param int $minRating
     * @param int $maxRating
     * @return self
     */
    public function betweenRatings(int $minRating, int $maxRating): self
    {
        return $this->whereBetween('number', [$minRating, $maxRating]);
    }

    /**
     * Get high ratings.
     *
     * @param int $threshold
     * @return self
     */
    public function highRatings(int $threshold = 8): self
    {
        return $this->where('number', '>=', $threshold);
    }

    /**
     * Get low ratings.
     *
     * @param int $threshold
     * @return self
     */
    public function lowRatings(int $threshold = 4): self
    {
        return $this->where('number', '<=', $threshold);
    }

    /**
     * Get ratings with reviews.
     *
     * @return self
     */
    public function withReviews(): self
    {
        return $this->whereNotNull('review')->where('review', '!=', '');
    }
}
