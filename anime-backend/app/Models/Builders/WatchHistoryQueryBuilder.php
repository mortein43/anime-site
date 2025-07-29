<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class WatchHistoryQueryBuilder extends Builder
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
     * Фільтрація за епізодом.
     *
     * @param int|string $episodeId
     * @return self
     */
    public function byEpisode($episodeId): self
    {
        return $this->where('episode_id', $episodeId);
    }

    /**
     * Тільки записи за останні N днів.
     *
     * @param int $days
     * @return self
     */
    public function fromLastDays(int $days): self
    {
        return $this->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Очистити історію, старішу за N днів (для даного користувача).
     *
     * @param int $days
     * @return int — кількість видалених записів
     */
    public function deleteOld(int $days = 30): int
    {
        return $this->where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Сортування за останніми переглядами.
     *
     * @return self
     */
    public function latestFirst(): self
    {
        return $this->orderByDesc('created_at');
    }

    /**
     * Сортування за найстарішими переглядами.
     *
     * @return self
     */
    public function oldestFirst(): self
    {
        return $this->orderBy('created_at');
    }

    /**
     * Завантажити зв'язаний епізод разом з історією.
     *
     * @return self
     */
    public function withEpisode(): self
    {
        return $this->with('episode');
    }
}

