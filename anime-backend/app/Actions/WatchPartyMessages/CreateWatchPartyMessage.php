<?php

namespace AnimeSite\Actions\WatchPartyMessages;

use AnimeSite\DTOs\WatchParties\CreateWatchPartyMessageDTO;
use AnimeSite\Models\WatchParty;
use AnimeSite\Models\WatchPartyMessage;
use Illuminate\Support\Facades\Auth;

class CreateWatchPartyMessage
{
    public function handle(WatchParty $watchParty, CreateWatchPartyMessageDTO $dto): WatchPartyMessage
    {
        return $watchParty->messages()->create([
            'user_id' => Auth::id(),
            'message' => $dto->message,
        ]);
    }
}
