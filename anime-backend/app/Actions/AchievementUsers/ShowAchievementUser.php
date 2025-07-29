<?php

namespace AnimeSite\Actions\AchievementUsers;

use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\AchievementUser;
/**
 * Отримати інформацію про досягнення конкретного користувача.
 */
class ShowAchievementUser
{
    public function __invoke(AchievementUser $achievementUser): AchievementUser
    {
        Gate::authorize('view', $achievementUser);
        return $achievementUser;
    }
}
