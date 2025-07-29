<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class SearchHistoryQueryBuilder extends Builder
{
    /**
     * Фільтрація за ID користувача.
     *
     * @param int|string $userId
     * @return self
     */
    public function byUser($userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Пошук за ключовим словом у запиті.
     *
     * @param string $keyword
     * @return self
     */
    public function whereQueryLike(string $keyword): self
    {
        return $this->where('query', 'like', '%' . $keyword . '%');
    }

    /**
     * Тільки історія, створена за останні N днів.
     *
     * @param int $days
     * @return self
     */
    public function fromLastDays(int $days = 30): self
    {
        return $this->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Сортувати за найновішими записами.
     *
     * @return self
     */
    public function latestFirst(): self
    {
        return $this->orderByDesc('created_at');
    }

    /**
     * Сортувати за найстарішими записами.
     *
     * @return self
     */
    public function oldestFirst(): self
    {
        return $this->orderBy('created_at');
    }
}
