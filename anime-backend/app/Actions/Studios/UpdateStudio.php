<?php

namespace AnimeSite\Actions\Studios;

use AnimeSite\DTOs\Studios\StudioUpdateDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Studio;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateStudio
{
    use AsAction;

    /**
     * Update an existing studio.
     *
     * @param  Studio  $studio
     * @param  StudioUpdateDTO  $dto
     * @return Studio
     */
    public function handle(Studio $studio, StudioUpdateDTO $dto): Studio
    {
        // Update the studio
        if ($dto->name !== null) {
            $studio->name = $dto->name;
        }

        if ($dto->description !== null) {
            $studio->description = $dto->description;
        }

        if ($dto->image !== null) {
            $studio->image = $studio->handleFileUpload($dto->image, 'studios', $studio->image);
        }

        if ($dto->slug !== null) {
            $studio->slug = $dto->slug;
        }

        if ($dto->metaTitle !== null) {
            $studio->meta_title = $dto->metaTitle;
        }

        if ($dto->metaDescription !== null) {
            $studio->meta_description = $dto->metaDescription;
        }

        if ($dto->metaImage !== null) {
            $studio->meta_image = $studio->handleFileUpload($dto->metaImage, 'meta', $studio->meta_image);
        }

        $studio->save();

        return $studio->loadCount('animes');
    }
}
