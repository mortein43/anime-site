<?php

namespace AnimeSite\Actions\Tags;

use AnimeSite\DTOs\Tags\TagIndexDTO;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use AnimeSite\Models\Selection;
use AnimeSite\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTags
{
    use AsAction;

    /**
     * Get paginated list of tags with sorting only.
     *
     * @param  TagIndexDTO  $dto
     * @return LengthAwarePaginator
     */
    public function handle(TagIndexDTO $dto): LengthAwarePaginator
    {
        $query = Tag::query()
            ->where('is_genre', false)
            ->withCount(['animes', 'people'])
            ->with([
                'animes' => fn ($q) => $q->select('animes.id', 'poster', 'name', 'slug', 'first_air_date', 'kind')->take(5),
                'people' => fn ($q) => $q->select('people.id', 'image')->take(5),
            ]);

        if ($dto->query) {
            $query->search($dto->query);
        }

        $sortField = $dto->sort ?? 'name';
        $direction = $dto->direction ?? 'asc';

        // Дозволені поля сортування
        $allowedSorts = [
            'name',             // алфавіт
            'created_at',       // популярність (за датою створення)
        ];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'name';
            $direction = 'asc';
        }

        $query->orderBy($sortField, $direction);

        return $query->paginate(
            perPage: $dto->perPage,
            page: $dto->page
        );
    }

}
