<?php

namespace AnimeSite\Http\Resources;

use AnimeSite\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for ratings in user context (without user relation)
 *
 * @mixin Rating
 */
class UserRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'anime_id' => $this->anime_id,
            'number' => $this->number,
            'review' => $this->review,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'anime' => new AnimeResource($this->whenLoaded('anime')),
        ];
    }
}
