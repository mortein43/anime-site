<?php

namespace AnimeSite\Http\Resources;

use AnimeSite\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Episode
 */
class EpisodeDetailResource extends JsonResource
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
            'anime' => [
                'name' => $this->anime?->name,
                'imdb_score' => $this->anime?->imdb_score,
                'description' => $this->anime?->description,
                'year' => $this->anime?->first_air_date?->format('Y'),
                'tags' => $this->anime?->tags->pluck('name'),
            ],
            'number' => $this->number,
            'name' => $this->name,
            'slug' => $this->slug,
            'full_name' => $this->full_name,
            'description' => strip_tags($this->description),
            'duration' => $this->duration,
            'formatted_duration' => $this->formatted_duration,
            'air_date' => $this->air_date?->format('Y-m-d'),
            'is_filler' => $this->is_filler,
            'pictures_url' => $this->pictures_url,
            'video_players' => $this->video_players,
            'comments_count' => $this->comments_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'seo' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_image' => $this->meta_image,
            ],
        ];
    }
}
