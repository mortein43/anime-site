<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class StudioQueryBuilder extends Builder
{
    /**
     * Filter by name.
     *
     * @param string $name
     * @return self
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', '%'.$name.'%');
    }

    /**
     * Get studios with animes.
     *
     * @return self
     */
    public function withAnimes(): self
    {
        return $this->whereHas('animes');
    }

    /**
     * Get studios with anime count.
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
        return $this->withAnimeCount()->orderBy('animes_count', $direction);
    }
}
