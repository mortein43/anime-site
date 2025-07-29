<?php

namespace AnimeSite\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class WatchPartyMessageQueryBuilder extends Builder
{
    /**
     * Фільтр по watch_party_id.
     */
    public function forWatchParty(string $watchPartyId): self
    {
        return $this->where('watch_party_id', $watchPartyId);
    }

    /**
     * Фільтр по user_id.
     */
    public function fromUser(string $userId): self
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Повідомлення за останні N хвилин.
     */
    public function recent(int $minutes = 60): self
    {
        return $this->where('created_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Відсортувати за часом створення (за зростанням).
     */
    public function ordered(): self
    {
        return $this->orderBy('created_at', 'asc');
    }
}
