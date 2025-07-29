<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class AchievementQueryBuilder extends Builder
{
    /**
     * Filter by name.
     *
     * @param string $name
     * @return self
     */
    public function byName(string $name): self
    {
        return $this->where('name', 'like', '%' . $name . '%');
    }

    /**
     * Filter by type.
     *
     * @param string $type
     * @return self
     */
    public function byType(string $type): self
    {
        return $this->where('type', $type);
    }

    /**
     * Only visible achievements.
     *
     * @return self
     */
    public function visible(): self
    {
        return $this->where('is_visible', true);
    }

    /**
     * Only hidden achievements.
     *
     * @return self
     */
    public function hidden(): self
    {
        return $this->where('is_visible', false);
    }

    /**
     * With users who earned the achievement.
     *
     * @return self
     */
    public function withUsers(): self
    {
        return $this->whereHas('users');
    }

    /**
     * With count of users who earned the achievement.
     *
     * @return self
     */
    public function withUsersCount(): self
    {
        return $this->withCount('users');
    }

    /**
     * Order achievements by number of users who earned them.
     *
     * @param string $direction
     * @return self
     */
    public function orderByUsersCount(string $direction = 'desc'): self
    {
        return $this->withUsersCount()->orderBy('users_count', $direction);
    }
}
