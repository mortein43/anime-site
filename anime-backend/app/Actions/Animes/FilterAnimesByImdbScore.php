<?php

namespace AnimeSite\Actions\Animes;

use AnimeSite\DTOs\Animes\AnimeFilterDTO;
use AnimeSite\Models\Anime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterAnimesByImdbScore
{
    use AsAction;

    /**
     * Filter movies by IMDb score.
     *
     * @param AnimeFilterDTO $dto
     * @return LengthAwarePaginator
     */
    public function handle(AnimeFilterDTO $dto): LengthAwarePaginator
    {
        $query = Anime::query()
            ->with('studio')
            ->where('imdb_score', '>=', $dto->score);

        // Apply additional filters
        if ($dto->kind) {
            $query->where('kind', $dto->kind);
        }

        if ($dto->status) {
            $query->where('status', $dto->status);
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
