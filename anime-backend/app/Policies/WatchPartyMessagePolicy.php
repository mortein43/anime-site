<?php

namespace AnimeSite\Policies;

use AnimeSite\Models\User;
use AnimeSite\Models\WatchPartyMessage;
use Illuminate\Auth\Access\Response;

class WatchPartyMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    public function view(User $user, WatchPartyMessage $message): bool
    {
        return $user->isAdmin() || $user->isModerator() || $user->id === $message->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, WatchPartyMessage $message): bool
    {
        return $user->isAdmin() || $user->isModerator() || $user->id === $message->user_id;
    }

    public function delete(User $user, WatchPartyMessage $message): bool
    {
        return $user->isAdmin() || $user->isModerator() || $user->id === $message->user_id;
    }

    public function restore(User $user, WatchPartyMessage $message): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    public function forceDelete(User $user, WatchPartyMessage $message): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }
}
