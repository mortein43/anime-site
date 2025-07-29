<?php

namespace AnimeSite\Http\Resources;

use AnimeSite\Http\Resources\EpisodeResource;
use AnimeSite\Http\Resources\PersonResource;
use AnimeSite\Http\Resources\SelectionResource;
use AnimeSite\Http\Resources\TagResource;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Person;
use AnimeSite\Models\Selection;
use AnimeSite\Models\Tag;
use AnimeSite\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for user lists in user context (without user relation)
 *
 * @mixin UserList
 */
class UserUserListResource extends JsonResource
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
            'type' => $this->type->value,
            'listable_type' => $this->listable_type,
            'listable_id' => $this->listable_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'listable' => $this->when($this->relationLoaded('listable'), function () {
                return match ($this->listable_type) {
                    Anime::class => new AnimeResource($this->listable),
                    Episode::class => new EpisodeResource($this->listable),
                    Person::class => new PersonResource($this->listable),
                    Tag::class => new TagResource($this->listable),
                    Selection::class => new SelectionResource($this->listable),
                    default => null,
                };
            }),
        ];
    }
}
