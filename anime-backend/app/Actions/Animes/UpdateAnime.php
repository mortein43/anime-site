<?php

namespace AnimeSite\Actions\Animes;

use AnimeSite\DTOs\Animes\AnimeUpdateDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Anime;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAnime
{
    use AsAction;

    /**
     * Update an existing anime.
     *
     * @param  Anime  $anime
     * @param  AnimeUpdateDTO  $dto
     * @return Anime
     */
    public function handle(Anime $anime, AnimeUpdateDTO $dto): Anime
    {
        if ($dto->name !== null) {
            $anime->name = $dto->name;
        }

        if ($dto->description !== null) {
            $anime->description = $dto->description;
        }

        if ($dto->kind !== null) {
            $anime->kind = $dto->kind;
        }

        if ($dto->status !== null) {
            $anime->status = $dto->status;
        }

        if ($dto->studioId !== null) {
            $anime->studio_id = $dto->studioId;
        }

        if ($dto->poster !== null) {
            $anime->poster = $anime->handleFileUpload($dto->poster, 'posters', $anime->poster);
        }

        if ($dto->backdrop !== null) {
            $anime->backdrop = $anime->handleFileUpload($dto->backdrop, 'backdrops', $anime->backdrop);
        }

        if ($dto->image_name !== null) {
            $anime->image_name = $anime->handleFileUpload($dto->image_name, 'animes', $anime->image_name);
        }

        if ($dto->countries !== null) {
            $anime->countries = $dto->countries;
        }

        if ($dto->aliases !== null) {
            $anime->aliases = $dto->aliases;
        }

        if ($dto->firstAirDate !== null) {
            $anime->first_air_date = $dto->firstAirDate;
        }

        if ($dto->lastAirDate !== null) {
            $anime->last_air_date = $dto->lastAirDate;
        }

        if ($dto->duration !== null) {
            $anime->duration = $dto->duration;
        }

        if ($dto->imdbScore !== null) {
            $anime->imdb_score = $dto->imdbScore;
        }

        if ($dto->isPublished !== null) {
            $anime->is_published = $dto->isPublished;
        }

        if ($dto->attachments !== null) {
            $anime->attachments = $anime->processAttachments($dto->attachments, 'attachments');
        }

        if ($dto->related !== null) {
            $anime->related = $dto->related;
        }

        if ($dto->similars !== null) {
            $anime->similars = $dto->similars;
        }

        if ($dto->apiSources !== null) {
            $anime->api_sources = $dto->apiSources;
        }

        if ($dto->slug !== null) {
            $anime->slug = $dto->slug;
        }

        if ($dto->metaTitle !== null) {
            $anime->meta_title = $dto->metaTitle;
        }

        if ($dto->metaDescription !== null) {
            $anime->meta_description = $dto->metaDescription;
        }

        if ($dto->metaImage !== null) {
            $anime->meta_image = $anime->handleFileUpload($dto->metaImage, 'meta', $anime->meta_image);
        }

        $anime->save();

        // Sync tags
        if ($dto->tagIds !== null) {
            $anime->tags()->sync($dto->tagIds);
        }

        // Sync persons
        if ($dto->personIds !== null) {
            $syncData = [];
            foreach ($dto->personIds as $personId => $pivotData) {
                if (is_array($pivotData)) {
                    $syncData[$personId] = $pivotData;
                } else {
                    $syncData[$pivotData] = ['character_name' => null];
                }
            }
            $anime->persons()->sync($syncData);
        }

        return $anime->load(['studio', 'tags', 'persons']);
    }
}

