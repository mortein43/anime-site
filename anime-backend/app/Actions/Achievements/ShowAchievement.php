<?php

namespace AnimeSite\Actions\Achievements;

use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\Achievement;
/**
 * Отримати інформацію про конкретне досягнення.
 */
class ShowAchievement
{
    public function __invoke(Achievement $achievement): Achievement
    {
        Gate::authorize('view', $achievement);
        return $achievement;
    }
}
