<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class AchievementUserQueryBuilder extends Builder
{
    /**
     * Фільтрація за користувачем.
     *
     * @param int|string $userId
     * @return self
     */
    public function byUser($userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Фільтрація за досягненням.
     *
     * @param int|string $achievementId
     * @return self
     */
    public function byAchievement($achievementId): self
    {
        return $this->where('achievement_id', $achievementId);
    }

    /**
     * Досягнення з певною мінімальною кількістю прогресу.
     *
     * @param int $count
     * @return self
     */
    public function withMinProgress(int $count): self
    {
        return $this->where('progress_count', '>=', $count);
    }

    /**
     * Сортувати за прогресом.
     *
     * @param string $direction
     * @return self
     */
    public function orderByProgress(string $direction = 'desc'): self
    {
        return $this->orderBy('progress_count', $direction);
    }

    /**
     * Фільтрувати лише досягнення, які завершено (тобто progress >= threshold).
     *
     * Якщо хочеш порівнювати з полем із пов'язаної моделі — доведеться джойнити.
     * Цей метод лише приклад і працює з жорстко заданим порогом.
     *
     * @param int $threshold
     * @return self
     */
    public function completed(int $threshold): self
    {
        return $this->where('progress_count', '>=', $threshold);
    }
}
