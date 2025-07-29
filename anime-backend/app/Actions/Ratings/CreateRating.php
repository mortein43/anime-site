<?php

namespace AnimeSite\Actions\Ratings;

use AnimeSite\DTOs\Ratings\RatingStoreDTO;
use AnimeSite\Models\Rating;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateRating
{
    use AsAction;

    /**
     * Create a new rating.
     *
     * @param  RatingStoreDTO  $dto
     * @return Rating
     */
    public function handle(RatingStoreDTO $dto): Rating
    {
        // Check if rating already exists
        $existingRating = Rating::where('user_id', $dto->userId)
            ->where('anime_id', $dto->animeId)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->number = $dto->number;
            $existingRating->review = $dto->review;
            $existingRating->save();

            return $existingRating;
        }

        // Create new rating
        $rating = new Rating();
        $rating->user_id = $dto->userId;
        $rating->anime_id = $dto->animeId;
        $rating->number = $dto->number;
        $rating->review = $dto->review;
        $rating->save();

        return $rating;
    }
}
