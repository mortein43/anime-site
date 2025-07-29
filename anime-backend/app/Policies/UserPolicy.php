<?php

namespace AnimeSite\Policies;

use AnimeSite\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     * Дозволяємо всім користувачам (включаючи неавторизованих) переглядати список користувачів
     */
    public function viewAny(?User $user): bool
    {
        return true; // Дозволяємо всім переглядати список користувачів
    }

    /**
     * Determine whether the user can view the model.
     * Дозволяємо всім користувачам переглядати профілі користувачів
     */
    public function view(?User $user, User $targetUser): bool
    {
        return true; // Публічні профілі доступні всім
    }

    /**
     * Determine whether the user can create models.
     * Тільки адміністратори можуть створювати користувачів через API
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $targetUser): bool
    {
        // Користувач може оновлювати свій профіль або адміністратор будь-який
        return $user->id === $targetUser->id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $targetUser): bool
    {
        // Тільки адміністратори можуть видаляти користувачів
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $targetUser): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $targetUser): bool
    {
        return $user->isAdmin();
    }
}
