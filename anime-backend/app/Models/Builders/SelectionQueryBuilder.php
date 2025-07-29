<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class SelectionQueryBuilder extends Builder
{
    /**
     * Get published selections.
     *
     * @return self
     */
    public function published(): self
    {
        return $this->where('is_published', true);
    }

    /**
     * Get unpublished selections.
     *
     * @return self
     */
    public function unpublished(): self
    {
        return $this->where('is_published', false);
    }

    /**
     * Get selections by user.
     *
     * @param string $userId
     * @return self
     */
    public function byUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Get selections with animes.
     *
     * @return self
     */
    public function withAnimes(): self
    {
        return $this->whereHas('animes');
    }

    /**
     * Get selections with persons.
     *
     * @return self
     */
    public function withPersons(): self
    {
        return $this->whereHas('persons');
    }

    /**
     * Get selections with comments.
     *
     * @return self
     */
    public function withComments(): self
    {
        return $this->whereHas('comments');
    }

    /**
     * Get selections with anime count.
     *
     * @return self
     */
    public function withAnimeCount(): self
    {
        return $this->withCount('animes');
    }

    /**
     * Order by anime count.
     *
     * @param string $direction
     * @return self
     */
    public function orderByAnimeCount(string $direction = 'desc'): self
    {
        return $this->withAnimesCount()->orderBy('animes_count', $direction);
    }
}
