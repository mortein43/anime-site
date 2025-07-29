<?php

namespace AnimeSite\Http\Resources;

use AnimeSite\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Studio
 */
class StudioResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => strip_tags($this->description),
            'image' => $this->image,
            'animes_count' => $this->when($this->animes_count !== null, fn() => $this->animes_count),
            'animes' => AnimeDetailResource::collection($this->whenLoaded('animes')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
