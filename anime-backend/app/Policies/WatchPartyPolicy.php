<?php

namespace AnimeSite\Policies;

use AnimeSite\Models\User;
use AnimeSite\Models\WatchParty;
use Illuminate\Auth\Access\Response;

class WatchPartyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    public function view(User $user, WatchParty $watchParty): bool
    {
        if ($user->isAdmin() || $user->isModerator()) {
            return true;
        }

        return !$watchParty->is_private;
    }

    public function create(User $user): bool
    {
        return true; // Можуть створювати всі авторизовані користувачі
    }

    public function update(User $user, WatchParty $watchParty): bool
    {
        return $user->isAdmin() || $user->isModerator() || $user->id === $watchParty->user_id;
    }

    public function delete(User $user, WatchParty $watchParty): bool
    {
        return $user->isAdmin() || $user->isModerator() || $user->id === $watchParty->user_id;
    }

    public function restore(User $user, WatchParty $watchParty): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    public function forceDelete(User $user, WatchParty $watchParty): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }
}
