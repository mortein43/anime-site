<?php

namespace AnimeSite\Actions\Selections;

use AnimeSite\DTOs\Selections\SelectionUpdateDTO;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Person;
use AnimeSite\Models\Selection;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSelection
{
    use AsAction;

    /**
     * Update an existing selection.
     *
     * @param  Selection  $selection
     * @param  SelectionUpdateDTO  $dto
     * @return Selection
     */
    public function handle(Selection $selection, SelectionUpdateDTO $dto): Selection
    {
        // Update the selection
        if ($dto->name !== null) {
            $selection->name = $dto->name;
        }

        if ($dto->description !== null) {
            $selection->description = $dto->description;
        }

        if ($dto->userId !== null) {
            $selection->user_id = $dto->userId;
        }

        if ($dto->isPublished !== null) {
            $selection->is_published = $dto->isPublished;
        }

        if ($dto->slug !== null) {
            $selection->slug = $dto->slug;
        }

        if ($dto->metaTitle !== null) {
            $selection->meta_title = $dto->metaTitle;
        }

        if ($dto->metaDescription !== null) {
            $selection->meta_description = $dto->metaDescription;
        }

        if ($dto->metaImage !== null) {
            $selection->meta_image = $selection->handleFileUpload($dto->metaImage, 'selections', $selection->meta_image);
        }

        $selection->save();

        // Sync movies if provided
        if ($dto->animeIds !== null) {
            $animes = Anime::whereIn('id', $dto->animeIds)->get();
            $selection->animes()->sync($animes);
        }

        // Sync persons if provided
        if ($dto->personIds !== null) {
            $persons = Person::whereIn('id', $dto->personIds)->get();
            $selection->persons()->sync($persons);
        }

        // Sync persons if provided
        if ($dto->episodeIds !== null) {
            $episodes = Episode::whereIn('id', $dto->episodeIds)->get();
            $selection->episodes()->sync($episodes);
        }

        return $selection->load(['user', 'animes', 'persons', 'episodes'])->loadCount(['animes', 'persons', 'episodes']);
    }
}
