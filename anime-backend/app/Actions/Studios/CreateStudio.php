<?php

namespace AnimeSite\Actions\Studios;

use AnimeSite\DTOs\Studios\StudioStoreDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Studio;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateStudio
{
    use AsAction;

    /**
     * Create a new studio.
     *
     * @param  StudioStoreDTO  $dto
     * @return Studio
     */
    public function handle(StudioStoreDTO $dto): Studio
    {
        // Create new studio
        $studio = new Studio();
        $studio->name = $dto->name;
        $studio->description = $dto->description;
        $studio->slug = $dto->slug;
        $studio->meta_title = $dto->metaTitle ?? Studio::makeMetaTitle($dto->name);
        $studio->meta_description = $dto->metaDescription ?? $dto->description;

        // Handle file uploads
        if ($dto->image) {
            $studio->image = $studio->handleFileUpload($dto->image, 'studios');
        }

        if ($dto->metaImage) {
            $studio->meta_image = $studio->handleFileUpload($dto->metaImage, 'meta');
        } else if ($dto->image) {
            // Use the main image as meta image if not provided
            $studio->meta_image = $studio->image;
        }

        $studio->save();

        return $studio;
    }
}
