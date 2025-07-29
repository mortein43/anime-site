<?php

namespace AnimeSite\Actions\Animes;

use AnimeSite\DTOs\Animes\AnimeStoreDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Anime;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAnime
{
    use AsAction;

    /**
     * Create a new anime.
     *
     * @param  AnimeStoreDTO  $dto
     * @return Anime
     */
    public function handle(AnimeStoreDTO $dto): Anime
    {
        // Create new anime
        $anime = new Anime();
        $anime->name = $dto->name;
        $anime->description = $dto->description;
        $anime->kind = $dto->kind;
        $anime->status = $dto->status;
        $anime->studio_id = $dto->studioId;
        $anime->countries = $dto->countries ?? [];
        $anime->aliases = $dto->aliases ?? [];
        $anime->first_air_date = $dto->firstAirDate;
        $anime->last_air_date = $dto->lastAirDate;
        $anime->duration = $dto->duration;
        $anime->imdb_score = $dto->imdbScore;
        $anime->is_published = $dto->isPublished;
        $anime->related = $dto->related ?? [];
        $anime->similars = $dto->similars ?? [];
        $anime->api_sources = $dto->apiSources ?? [];
        $anime->slug = $dto->slug;
        $anime->meta_title = $dto->metaTitle ?? $dto->name;
        $anime->meta_description = $dto->metaDescription ?? $dto->description;

        // Handle file uploads
        if ($dto->poster) {
            $anime->poster = $anime->handleFileUpload($dto->poster, 'posters');
        }

        if ($dto->image_name) {
            $anime->image_name = $anime->handleFileUpload($dto->image_name, 'animes');
        }

        if ($dto->metaImage) {
            $anime->meta_image = $anime->handleFileUpload($dto->metaImage, 'meta');
        }

        // Process attachments if any
        if ($dto->attachments) {
            $anime->attachments = $anime->processAttachments($dto->attachments, 'attachments');
        } else {
            $anime->attachments = [];
        }

        $anime->save();

        // Sync tags if provided
        if ($dto->tagIds) {
            $anime->tags()->sync($dto->tagIds);
        }

        // Sync persons if provided
        if ($dto->personIds) {
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
