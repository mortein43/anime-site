<?php

namespace AnimeSite\Actions\Selections;

use AnimeSite\DTOs\Selections\SelectionStoreDTO;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use AnimeSite\Models\Selection;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSelection
{
    use AsAction;

    /**
     * Create a new selection.
     *
     * @param  SelectionStoreDTO  $dto
     * @return Selection
     */
    public function handle(SelectionStoreDTO $dto): Selection
    {
        // Create new selection
        $selection = new Selection();
        $selection->name = $dto->name;
        $selection->description = $dto->description;
        $selection->user_id = $dto->userId;
        $selection->is_published = $dto->isPublished;
        $selection->slug = $dto->slug;
        $selection->meta_title = $dto->metaTitle ?? $dto->name;
        $selection->meta_description = $dto->metaDescription ?? $dto->description;

        // Handle file uploads
        if ($dto->metaImage) {
            $selection->meta_image = $selection->handleFileUpload($dto->metaImage, 'selections');
        }

        $selection->save();

        // Attach animes if provided
        if (!empty($dto->animeIds)) {
            $animes = Anime::whereIn('id', $dto->animeIds)->get();
            $selection->animes()->attach($animes);
        }

        // Attach persons if provided
        if (!empty($dto->personIds)) {
            $persons = Person::whereIn('id', $dto->personIds)->get();
            $selection->persons()->attach($persons);
        }

        // Attach episodes if provided
        if (!empty($dto->episodeIds)) {
            $episodes = Person::whereIn('id', $dto->episodeIds)->get();
            $selection->episodes()->attach($episodes);
        }

        return $selection->load(['user', 'animes', 'persons', 'episodes'])->loadCount(['animes', 'persons', 'episodes']);
    }
}
