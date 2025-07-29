<?php

namespace AnimeSite\Actions\Animes;

use AnimeSite\DTOs\Animes\PopularsAnimesDTO;
use AnimeSite\Enums\Kind;
use AnimeSite\Models\Anime;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class GetPopularAnimes
{
    use AsAction;

    /**
     * Get popular movies.
     *
     * @param PopularsAnimesDTO $dto
     * @return Collection
     */
    public function handle(PopularsAnimesDTO $dto): Collection
    {
        return Anime::where('kind', Kind::TV_SERIES)
            ->orderBy('imdb_score', 'desc')
            ->take($dto->limit)
            ->get();
    }
}
