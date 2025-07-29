<?php

namespace AnimeSite\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeopleResource extends JsonResource
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
            'biography' => strip_tags($this->description),
            'image' => $this->image,
            'type' => $this->type->value,
            'gender' => $this->gender?->value,
            'birth_date' => $this->birthday,
            'death_date' => null, // Поле death_date відсутнє в моделі
            'animes_count' => $this->when(isset($this->animes_count), $this->animes_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
