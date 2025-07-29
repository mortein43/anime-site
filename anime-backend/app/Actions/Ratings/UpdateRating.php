<?php

namespace AnimeSite\Actions\Ratings;

use AnimeSite\DTOs\Ratings\RatingUpdateDTO;
use AnimeSite\Models\Rating;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateRating
{
    use AsAction;

    /**
     * Update an existing rating.
     *
     * @param  Rating  $rating
     * @param  RatingUpdateDTO  $dto
     * @return Rating
     */
    public function handle(Rating $rating, RatingUpdateDTO $dto): Rating
    {
        // Update rating
        if ($dto->number !== null) {
            $rating->number = $dto->number;
        }

        if ($dto->review !== null) {
            $rating->review = $dto->review;
        }

        $rating->save();

        return $rating;
    }
}
