<?php

namespace AnimeSite\Models\Builders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EpisodeQueryBuilder extends Builder
{
    /**
     * Filter episodes by anime ID.
     *
     * @param string $animeId
     * @return self
     */
    public function forAnime(string $animeId): self
    {
        return $this->where('anime_id', $animeId);
    }

    /**
     * Filter episodes by air date.
     *
     * @param Carbon $date
     * @return self
     */
    public function airedAfter(Carbon $date): self
    {
        return $this->where('air_date', '>=', $date);
    }

    /**
     * Filter filler episodes.
     *
     * @param bool $includeFiller Whether to include filler episodes
     * @return self
     */
    public function fillers(bool $includeFiller = false): self
    {
        return $includeFiller ? $this : $this->where('is_filler', false);
    }

    /**
     * Get recently aired episodes.
     *
     * @param int $days
     * @return self
     */
    public function recentlyAired(int $days = 7): self
    {
        $date = Carbon::now()->subDays($days);
        return $this->where('air_date', '>=', $date)
            ->orderBy('air_date', 'desc');
    }

    /**
     * Order episodes by number.
     *
     * @param string $direction
     * @return self
     */
    public function orderByNumber(string $direction = 'asc'): self
    {
        return $this->orderBy('number', $direction);
    }

    /**
     * Apply chaperone mode (filter out episodes that are not suitable for all audiences).
     *
     * @return self
     */
    public function chaperone(): self
    {
        // In a real application, this would filter out episodes based on some criteria
        // For now, we'll just return the query as is
        return $this;
    }
}
