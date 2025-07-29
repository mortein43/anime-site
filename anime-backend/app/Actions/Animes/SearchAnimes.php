<?php

namespace AnimeSite\Actions\Animes;

use AnimeSite\DTOs\Animes\AnimeSearchDTO;
use AnimeSite\Models\Anime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;

class SearchAnimes
{
    use AsAction;

    /**
     * Search movies with filtering, sorting and pagination.
     *
     * @param AnimeSearchDTO $dto
     * @return LengthAwarePaginator
     */
    public function handle(AnimeSearchDTO $dto): LengthAwarePaginator
    {
        $query = Anime::search($dto->query);

        // Apply filters to the search results
        $query = Anime::query()
            ->with('studio')
            ->whereIn('id', $query->keys());

        // Apply additional filters
        if ($dto->kind) {
            $query->where('kind', $dto->kind);
        }

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if ($dto->minScore !== null) {
            $query->where('imdb_score', '>=', $dto->minScore);
        }

        if ($dto->maxScore !== null) {
            $query->where('imdb_score', '<=', $dto->maxScore);
        }

        if ($dto->studioId) {
            $query->where('studio_id', $dto->studioId);
        }

        if ($dto->tagId) {
            $query->whereHas('tags', function ($q) use ($dto) {
                $q->where('tags.id', $dto->tagId);
            });
        }

        if ($dto->personId) {
            $query->whereHas('persons', function ($q) use ($dto) {
                $q->where('people.id', $dto->personId);
            });
        }

        // Apply sorting
        $query->orderBy($dto->sort ?? 'created_at', $dto->direction ?? 'desc');

        // Return paginated results
        return $query->paginate(
            perPage: $dto->perPage,
            page: $dto->page
        );
    }
}
