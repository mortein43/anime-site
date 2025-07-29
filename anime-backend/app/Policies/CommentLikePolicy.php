<?php

namespace AnimeSite\Policies;

use AnimeSite\Models\CommentLike;
use AnimeSite\Models\User;

class CommentLikePolicy
{
    /**
     * Determine whether the user can view any models.
     * Параметр $user може бути null для неавторизованих користувачів
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Параметр $user може бути null для неавторизованих користувачів
     */
    public function view(?User $user, CommentLike $commentLike): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CommentLike $commentLike): bool
    {
        return $user->id === $commentLike->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CommentLike $commentLike): bool
    {
        return $user->id === $commentLike->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CommentLike $commentLike): bool
    {
        return $user->id === $commentLike->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CommentLike $commentLike): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }
}
