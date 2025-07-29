<?php

namespace AnimeSite\Actions\Episodes;

use AnimeSite\DTOs\Episodes\EpisodeStoreDTO;
use AnimeSite\Models\Episode;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateEpisode
{
    use AsAction;

    /**
     * Create a new episode.
     *
     * @param  EpisodeStoreDTO  $dto
     * @return Episode
     */
    public function handle(EpisodeStoreDTO $dto): Episode
    {
        // Create new episode
        $episode = new Episode();
        $episode->anime_id = $dto->animeId;
        $episode->number = $dto->number;
        $episode->name = $dto->name;
        $episode->description = $dto->description;
        $episode->duration = $dto->duration;
        $episode->air_date = $dto->airDate;
        $episode->is_filler = $dto->isFiller;
        $episode->video_players = $dto->videoPlayers;
        $episode->slug = $dto->slug;
        $episode->meta_title = $dto->metaTitle ?? $dto->name;
        $episode->meta_description = $dto->metaDescription ?? $dto->description;

        // Handle file uploads
        if ($dto->pictures) {
            $episode->pictures = $episode->processFilesArray($dto->pictures, 'episodes');
        } else {
            $episode->pictures = [];
        }

        if ($dto->metaImage) {
            $episode->meta_image = $episode->handleFileUpload($dto->metaImage, 'meta');
        } else if (!empty($episode->pictures)) {
            // Use the first picture as meta image if not provided
            $episode->meta_image = is_array($episode->pictures) ? reset($episode->pictures) : $episode->pictures;
        }

        $episode->save();

        return $episode->load(['anime']);
    }
}
