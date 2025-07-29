<?php

namespace AnimeSite\Actions\Selections;

use AnimeSite\DTOs\Selections\SelectionIndexDTO;
use AnimeSite\Models\Selection;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSelections
{
    use AsAction;

    /**
     * Get paginated list of selections with filtering, searching, and sorting.
     *
     * @param  SelectionIndexDTO  $dto
     * @return LengthAwarePaginator
     */
    public function handle(SelectionIndexDTO $dto): LengthAwarePaginator
    {
        // Start with base query
        $query = Selection::query()->withCount(['animes', 'userLists']);

        // Apply search if query is provided
        if ($dto->query) {
            $query->where('name', 'like', "%{$dto->query}%");
        }

        // Apply filters
        if ($dto->isPublished !== null) {
            if ($dto->isPublished) {
                $query->published();
            } else {
                $query->unpublished();
            }
        }

        if ($dto->userId) {
            $query->byUser($dto->userId);
        }

        if ($dto->hasAnimes) {
            $query->withAnimes();
        }

        if ($dto->hasPersons) {
            $query->withPersons();
        }

        if ($dto->hasEpisodes) {
            $query->withEpisodes();
        }

        if ($dto->animeIds) {
            $query->whereHas('animes', function ($q) use ($dto) {
                $q->whereIn('animes.id', $dto->animeIds);
            });
        }

        if ($dto->personIds) {
            $query->whereHas('persons', function ($q) use ($dto) {
                $q->whereIn('people.id', $dto->personIds);
            });
        }

        if ($dto->episodeIds) {
            $query->whereHas('episodes', function ($q) use ($dto) {
                $q->whereIn('episodes.id', $dto->episodeIds);
            });
        }

        // Apply sorting
        $sortField = $dto->sort ?? 'created_at';
        $direction = $dto->direction ?? 'desc';

        if ($sortField === 'animes_count') {
            $query->orderByAnimeCount($direction);
        } elseif ($sortField === 'user_lists_count') {
            $query->orderBy('user_lists_count', $direction);
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
