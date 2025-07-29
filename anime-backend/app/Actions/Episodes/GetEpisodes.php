<?php

namespace AnimeSite\Actions\Episodes;

use AnimeSite\DTOs\Episodes\EpisodeIndexDTO;
use AnimeSite\Models\Episode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetEpisodes
{
    /**
     * Get episodes based on the provided DTO
     *
     * @param EpisodeIndexDTO $dto
     * @return LengthAwarePaginator
     */
    public function handle(EpisodeIndexDTO $dto): LengthAwarePaginator
    {
        $query = Episode::query();

        // Apply filters
        if ($dto->animeId) {
            $query->forAnime($dto->animeId);
        }

        if ($dto->airedAfter) {
            $query->airedAfter($dto->airedAfter);
        }

        $query->fillers($dto->includeFiller);

        // Apply sorting
        if ($dto->sort) {
            if ($dto->sort === 'number') {
                $query->orderByNumber($dto->direction);
            } else {
                $query->orderBy($dto->sort, $dto->direction);
            }
        } else {
            // Default sorting by number
            $query->orderByNumber();
        }

        // Paginate results
        return $query->paginate($dto->perPage);
    }
}
