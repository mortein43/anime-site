<?php

namespace AnimeSite\Policies;

use AnimeSite\Models\Selection;
use AnimeSite\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SelectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Добірки можуть переглядати всі користувачі, включаючи гостей
    }

    /**
     * Determine whether the user can view selections for a specific user.
     * Перевіряє чи можна переглядати добірки конкретного користувача
     */
    public function viewUserSelections(?User $currentUser, User $targetUser): bool
    {
        // Власник завжди може переглядати свої добірки
        if ($currentUser && $currentUser->id === $targetUser->id) {
            return true;
        }

        // Адміністратори та модератори можуть переглядати всі добірки
        if ($currentUser && ($currentUser->isAdmin() || $currentUser->isModerator())) {
            return true;
        }

        // Інші користувачі можуть переглядати тільки опубліковані добірки
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Selection $selection): bool
    {
        // Перевіряємо, чи добірка опублікована
        if ($selection->is_published) {
            return true;
        }

        // Якщо користувач не авторизований, він не може переглядати неопубліковані добірки
        if (!$user) {
            return false;
        }

        // Власник, адміністратор або модератор може переглядати будь-які добірки
        return $user->id === $selection->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Тільки авторизовані користувачі можуть створювати добірки
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Selection $selection): bool
    {
        return $user->id === $selection->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Selection $selection): bool
    {
        return $user->id === $selection->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Selection $selection): bool
    {
        return $user->id === $selection->user_id || $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Selection $selection): bool
    {
        return $user->id === $selection->user_id || $user->isAdmin() || $user->isModerator();
    }
}
