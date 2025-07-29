<?php

namespace AnimeSite\Enums;

enum WatchPartyStatus: string
{
    case WAITING = 'waiting';      // Очікує учасників
    case ACTIVE = 'active';        // Відео відтворюється
    case ENDED = 'ended';          // Перегляд завершено

    public function label(): string
    {
        return match ($this) {
            self::WAITING => 'Очікування',
            self::ACTIVE => 'Активна',
            self::ENDED => 'Завершено',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::WAITING => 'gray',
            self::ACTIVE => 'success',
            self::ENDED => 'danger',
        };
    }
}
