<?php

namespace AnimeSite\Actions\Studios;

use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use AnimeSite\DTOs\Studios\StudioIndexDTO;
use AnimeSite\Models\Studio;
use Lorisleiva\Actions\Concerns\AsAction;

class GetStudios
{
    use AsAction;

    /**
     * Get paginated list of studios with filtering, searching, and sorting.
     *
     * @param  StudioIndexDTO  $dto
     * @return LengthAwarePaginator
     */
    public function handle(StudioIndexDTO $dto): LengthAwarePaginator
    {
        // Start with base query
        $query = Studio::query()->withAnimeCount();

        // Apply search if query is provided
        if ($dto->query) {
            $query->byName($dto->query);
        }

        // Apply filters
        if ($dto->hasAnimes) {
            $query->withAnimes();
        }

        if ($dto->animeIds) {
            $query->whereHas('animes', function ($q) use ($dto) {
                $q->whereIn('animes.id', $dto->animeIds);
            });
        }

        // Apply sorting
        $sortField = $dto->sort ?? 'created_at';
        $direction = $dto->direction ?? 'desc';

        if ($sortField === 'animes_count') {
            $query->orderByAnimeCount($direction);
        } else {
            $query->orderBy($sortField, $direction);
        }

        // Return paginated results
        return $query->paginate(
            perPage: $dto->perPage,
            page: $dto->page
        );
    }
}
