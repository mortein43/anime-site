<?php

namespace AnimeSite\Http\Resources;

use AnimeSite\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Episode
 */
class EpisodeResource extends JsonResource
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
            'anime_id' => $this->anime_id,
            'number' => $this->number,
            'name' => $this->name,
            'slug' => $this->slug,
            'full_name' => $this->full_name,
            'description' => strip_tags($this->description),
            'duration' => $this->duration,
            'formatted_duration' => $this->formatted_duration,
            'air_date' => $this->air_date?->format('Y-m-d'),
            'is_filler' => $this->is_filler,
            'picture_url' => $this->picture_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
