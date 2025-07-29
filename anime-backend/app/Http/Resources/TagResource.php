<?php

namespace AnimeSite\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => strip_tags($this->description),
            'image' => $this->image,
            'is_genre' => $this->is_genre,
            'aliases' => $this->aliases,

            // Динамічне додавання *_count, якщо воно є
            'animes_count' => $this->when(isset($this->animes_count), $this->animes_count),
            'people_count' => $this->when(isset($this->people_count), $this->people_count),
            'animes' => $this->animes->map(function ($anime) {
                return [
                    'poster' => $anime->poster,
                    'name' => $anime->name,
                    'slug' => $anime->slug,
                    'year' => optional($anime->first_air_date)->format('Y'),
                    'kind' => $anime->kind,
                ];
            }),

            'people' => $this->people->map(function ($person) {
                return [
                    'image' => $person->image,
                    'name' => $person->name,
                    'age' => $person->birthday
                        ? now()->diffInYears($person->birthday)
                        : null,
                    'type' => $person->type->name(),
                ];
            }),
            //'taggables_count' => $this->when(isset($this->taggables_count), $this->taggables_count),
            //'animes' => AnimeResource::collection($this->whenLoaded('animes')),
            //'people' => PersonResource::collection($this->whenLoaded('people')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
