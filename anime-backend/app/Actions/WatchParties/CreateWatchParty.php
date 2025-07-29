<?php

namespace AnimeSite\Actions\WatchParties;

use AnimeSite\DTOs\WatchParties\CreateWatchPartyDTO;
use AnimeSite\Models\WatchParty;
use Illuminate\Support\Str;
use AnimeSite\Enums\WatchPartyStatus;

class CreateWatchParty
{
    public function handle(CreateWatchPartyDTO $dto): WatchParty
    {
        $password = $dto->isPrivate ? $dto->password : null;

        $watchParty = WatchParty::create([
            'name' => $dto->name,
            'slug' => Str::random(6),
            'user_id' => auth()->id(),
            'episode_id' => $dto->episodeId,
            'is_private' => $dto->isPrivate,
            'password' => $password,
            'max_viewers' => $dto->maxViewers,
            'watch_party_status' => WatchPartyStatus::WAITING,
        ]);

        // Зберігаємо plain_password, щоб можна було повернути, якщо потрібно
        $watchParty->plain_password = $password;

        return $watchParty;
    }
}
