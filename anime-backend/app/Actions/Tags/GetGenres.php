<?php

namespace AnimeSite\Actions\Tags;

use AnimeSite\DTOs\Tags\TagIndexDTO;
use AnimeSite\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

class GetGenres
{
    public function handle(TagIndexDTO $dto): LengthAwarePaginator
    {
        $query = Tag::query()
            ->withCount(['animes', 'people'])
            ->where('is_genre', true)
            ->with([
                'animes' => fn ($q) => $q->select('animes.id', 'poster')->take(5),
                'people' => fn ($q) => $q->select('people.id', 'image')->take(5),
            ]);

        if ($dto->query) {
            $query->search($dto->query);
        }

        $sortField = $dto->sort ?? 'name';
        $direction = $dto->direction ?? 'asc';

        $allowedSorts = [
            'name',
            'created_at',
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
