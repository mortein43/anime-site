<?php

namespace AnimeSite\Actions\Animes;

use AnimeSite\Enums\Kind;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterAnimesByKind
{
    use AsAction;

    /**
     * Filter movies by kind.
     *
     * @param MovieFilterDTO $dto
     * @return LengthAwarePaginator
     */
    public function handle(MovieFilterDTO $dto): LengthAwarePaginator
    {
        $query = Movie::query()
            ->with('studio')
            ->where('kind', $dto->kind);

        // Apply additional filters
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
