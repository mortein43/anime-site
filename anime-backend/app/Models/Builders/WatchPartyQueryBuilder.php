<?php

namespace AnimeSite\Models\Builders;

use AnimeSite\Enums\WatchPartyStatus;
use Illuminate\Database\Eloquent\Builder;

class WatchPartyQueryBuilder extends Builder
{
    /**
     * Фільтрація за статусом (WAITING, ACTIVE, ENDED).
     */
    public function whereStatus(WatchPartyStatus $status): self
    {
        return $this->where('watch_party_status', $status);
    }

    /**
     * Тільки активні кімнати.
     */
    public function active(): self
    {
        return $this->whereStatus(WatchPartyStatus::ACTIVE);
    }

    /**
     * Тільки очікуючі кімнати.
     */
    public function waiting(): self
    {
        return $this->whereStatus(WatchPartyStatus::WAITING);
    }

    /**
     * Тільки завершені кімнати.
     */
    public function ended(): self
    {
        return $this->whereStatus(WatchPartyStatus::ENDED);
    }

    /**
     * Фільтрація за slug.
     */
    public function whereSlug(string $slug): self
    {
        return $this->where('slug', $slug);
    }

    /**
     * Пошук за частковою назвою.
     */
    public function whereNameLike(string $name): self
    {
        return $this->where('name', 'like', '%' . $name . '%');
    }

    /**
     * Тільки публічні кімнати.
     */
    public function public(): self
    {
        return $this->where('is_private', false);
    }

    /**
     * Тільки приватні кімнати.
     */
    public function private(): self
    {
        return $this->where('is_private', true);
    }

    /**
     * Кімнати, створені користувачем.
     */
    public function byUserId(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Початі за останні N хвилин.
     */
    public function startedWithinMinutes(int $minutes): self
    {
        return $this->where('started_at', '>=', now()->subMinutes($minutes));
    }
}
