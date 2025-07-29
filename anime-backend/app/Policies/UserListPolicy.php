<?php

namespace AnimeSite\Policies;

use AnimeSite\Models\User;
use AnimeSite\Models\UserList;

class UserListPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return $user && ($user->isAdmin() || $user->isModerator());
    }

    /**
     * Determine whether the user can view user lists for a specific user.
     * Перевіряє чи можна переглядати списки конкретного користувача
     */
    public function viewUserLists(?User $currentUser, User $targetUser): bool
    {
        // Власник завжди може переглядати свої списки
        // Адміністратори та модератори можуть переглядати всі списки
        if (
            $currentUser &&
            (
                $currentUser->id === $targetUser->id ||
                $currentUser->isAdmin() ||
                $currentUser->isModerator()
            )
        ) {
            return true;
        }

        // Якщо у користувача приватні списки, то вони недоступні для перегляду
        // Інакше списки публічні
        return !$targetUser->is_private_favorites;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, UserList $userList): bool
    {
        // Власник списку завжди може переглядати свої списки
        // Адміністратори та модератори можуть переглядати всі списки
        if (
            $user &&
            (
                $user->id === $userList->user_id ||
                $user->isAdmin() ||
                $user->isModerator()
            )
        ) {
            return true;
        }

        // Якщо власник списку має приватні списки, то вони недоступні для перегляду
        // Інакше списки публічні
        return !$userList->user->is_private_favorites;
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
    public function update(User $user, UserList $userList): bool
    {
        return $user->id === $userList->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserList $userList): bool
    {
        return $user->id === $userList->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserList $userList): bool
    {
        return $user->id === $userList->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserList $userList): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }
}
