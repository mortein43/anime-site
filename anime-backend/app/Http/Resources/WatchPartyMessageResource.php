<?php

namespace AnimeSite\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WatchPartyMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
