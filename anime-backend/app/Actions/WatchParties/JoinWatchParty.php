<?php

namespace AnimeSite\Actions\WatchParties;

use AnimeSite\Models\WatchParty;
use Illuminate\Support\Facades\Auth;

class JoinWatchParty
{
    public function handle(string $slug, string $name, ?string $password): WatchParty
    {
        $watchParty = WatchParty::where('slug', $slug)->firstOrFail();

        if ($watchParty->name !== $name) {
            abort(403, 'Invalid room name.');
        }

        if ($watchParty->is_private && $watchParty->password !== $password) {
            abort(403, 'Invalid password.');
        }

        if ($watchParty->users()->count() >= $watchParty->max_viewers) {
            abort(403, 'Room is full.');
        }

        $watchParty->users()->syncWithoutDetaching([
            Auth::id() => ['joined_at' => now()],
        ]);

        return $watchParty;
    }
}
