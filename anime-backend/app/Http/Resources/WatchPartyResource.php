<?php

namespace AnimeSite\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class WatchPartyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'episode_id' => $this->episode_id,
            'user_id' => $this->user_id,
            'is_private' => $this->is_private,
            'max_viewers' => $this->max_viewers,
            'status' => $this->watch_party_status,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'created_at' => $this->created_at,
            'password' => $this->when(auth()->id() === $this->user_id && property_exists($this, 'plain_password'), fn () => $this->plain_password),
        ];
    }
}
