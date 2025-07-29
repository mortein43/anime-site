<?php

namespace AnimeSite\Actions\AchievementUsers;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\AchievementUser;
/**
 * Отримати інформацію про досягнення користувача.
 */
class UpdateAchievementUser
{
    /**
     * @param AchievementUser $achievementUser
     * @param array{
     *     progress_count?: int
     * } $data
     */
    public function __invoke(AchievementUser $achievementUser, array $data): AchievementUser
    {
        Gate::authorize('update', $achievementUser);

        return DB::transaction(function () use ($achievementUser, $data) {
            $achievementUser->update($data);
            return $achievementUser;
        });
    }
}
