<?php

namespace AnimeSite\Actions\People;

use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use AnimeSite\DTOs\People\PersonIndexDTO;
use AnimeSite\Models\Person;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllPeople
{
    use AsAction;

    /**
     * Get paginated list of persons with filtering, searching, and sorting.
     *
     * @param  PersonIndexDTO  $dto
     * @return LengthAwarePaginator
     */
    public function handle(PersonIndexDTO $dto): LengthAwarePaginator
    {
        // Start with base query
        $query = Person::query()->withAnimeCount();

        // Apply search if query is provided
        if ($dto->query) {
            $query->byName($dto->query);
        }

        // Apply filters
        if ($dto->types) {
            $query->whereIn('type', $dto->types);
        }

        if ($dto->genders) {
            $query->whereIn('gender', $dto->genders);
        }

        if ($dto->animeIds) {
            $query->whereHas('animes', function ($q) use ($dto) {
                $q->whereIn('animes.id', $dto->animeIds);
            });
        }

        // Apply age filters if provided
        if ($dto->minAge !== null || $dto->maxAge !== null) {
            if ($dto->minAge !== null) {
                $maxDate = now()->subYears($dto->minAge);
                $query->where('birthday', '<=', $maxDate);
            }

            if ($dto->maxAge !== null) {
                $minDate = now()->subYears($dto->maxAge + 1)->addDay();
                $query->where('birthday', '>=', $minDate);
            }
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
