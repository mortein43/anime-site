<?php

namespace AnimeSite\Actions\Search;

use AnimeSite\DTOs\Search\AutocompleteDTO;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use AnimeSite\Models\Tag;
use Lorisleiva\Actions\Concerns\AsAction;

class PerformAutocomplete
{
    use AsAction;

    /**
     * Execute the autocomplete search.
     *
     * @param AutocompleteDTO $dto
     * @return array
     */
    public function handle(AutocompleteDTO $dto): array
    {
        // Якщо запит порожній або занадто короткий, повертаємо порожній масив
        if (empty($dto->query) || strlen($dto->query) < 2) {
            return [];
        }

        // Використовуємо колекцію для збору результатів
        return collect()
            ->when(true, function ($collection) use ($dto) {
                return $collection->merge($this->getAnimes($dto->query));
            })
            ->when(true, function ($collection) use ($dto) {
                return $collection->merge($this->getPeople($dto->query));
            })
            ->when(true, function ($collection) use ($dto) {
                return $collection->merge($this->getTags($dto->query));
            })
            ->toArray();
    }

    /**
     * Get anime autocomplete results.
     *
     * @param string $query
     * @return array
     */
    private function getAnimes(string $query): array
    {
        return Anime::search($query)
            ->take(5)
            ->get()
            ->map(function ($anime) {
                return [
                    'id' => $anime->id,
                    'text' => $anime->name,
                    'type' => 'anime',
                    'image' => $anime->image_name,
                    'url' => "/animes/{$anime->slug}",
                ];
            })
            ->toArray();
    }

    /**
     * Get person autocomplete results.
     *
     * @param string $query
     * @return array
     */
    private function getPeople(string $query): array
    {
        return Person::search($query)
            ->take(5)
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'text' => $person->name,
                    'type' => 'person',
                    'image' => $person->image,
                    'url' => "/people/{$person->slug}",
                ];
            })
            ->toArray();
    }

    /**
     * Get tag autocomplete results.
     *
     * @param string $query
     * @return array
     */
    private function getTags(string $query): array
    {
        return Tag::search($query)
            ->take(3)
            ->get()
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'text' => $tag->name,
                    'type' => 'tag',
                    'image' => $tag->image,
                    'url' => "/tags/{$tag->slug}",
                ];
            })
            ->toArray();
    }
}
