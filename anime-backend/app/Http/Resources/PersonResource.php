<?php

namespace AnimeSite\Http\Resources;

use AnimeSite\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Person
 */
class PersonResource extends JsonResource
{
    protected $voiceActors;

    public function __construct($resource, $voiceActors = null)
    {
        parent::__construct($resource);
        $this->voiceActors = $voiceActors;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'original_name' => $this->original_name,
            'image' => $this->image,
            'biography' => strip_tags($this->description),
            'birth_date' => $this->birthday
                ? $this->birthday->translatedFormat('j F Y') . ' року'
                : null,
            'birthplace' => $this->birthplace,
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'image' => $this->meta_image,
            ],
            'type' => $this->type->name(),
            'gender' => $this->gender->name(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'animes' => $this->whenLoaded('animes', function () {
                return $this->animes->map(function ($anime) {
                    $voiceActorId = $anime->pivot->voice_person_id;
                    $voiceActor = $voiceActorId ? $this->voiceActors[$voiceActorId] ?? null : null;

                    return [
                        'id' => $anime->id,
                        'slug' => $anime->slug,
                        'title' => $anime->name,
                        'year' => optional($anime->first_air_date)->year,
                        'poster' => $anime->poster,
                        'kind' => $anime->kind->name(),
                        'character_name' => $anime->pivot->character_name,
                        'voice_actor' => $voiceActor
                            ? [
                                'id' => $voiceActor->id,
                                'name' => $voiceActor->name,
                                'image' => $voiceActor->image,
                            ]
                            : null,
                    ];
                });
            }),
        ];
    }
}
