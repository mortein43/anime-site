<?php

namespace AnimeSite\Enums;

enum NotificationType: string
{
    // Нові епізоди
    case NotifyNewEpisodes = 'Вийшла нова серія!';
    case NotifyEpisodeDateChanges = 'Змінилась дата виходу епізоду.';
    case NotifyAnnouncementToOngoing = 'Оголошення для поточних серіалів.';

    // Повідомлення про коментарі
    case NotifyCommentReplies = 'Хтось відповів на ваш коментар.';
    case NotifyCommentLikes = 'Ваш коментар отримав лайк.';

    // Повідомлення про огляди / рецензії
    case NotifyReviewReplies = 'Хтось відповів на ваш огляд.';

    // Повідомлення про список користувача
    case NotifyPlannedReminders = 'Нагадування про заплановані аніме.';

    // Повідомлення про підбірки
    case NotifyNewSelections = 'Додано нові підбірки аніме.';

    // Повідомлення про фільми
    case NotifyStatusChanges = 'Змінився статус фільму.';
    case NotifyNewSeasons = 'Вийшов новий сезон аніме.';

    // Повідомлення про підписку
    case NotifySubscriptionExpiration = 'Ваша підписка скоро закінчиться.';
    case NotifySubscriptionRenewal = 'Ваша підписка була оновлена.';
    case NotifyPaymentIssues = 'Проблеми з оплатою підписки.';
    case NotifyTariffChanges = 'Зміни тарифного плану.';

    // Системні повідомлення
    case NotifySiteUpdates = 'Оновлення сайту доступні.';
    case NotifyMaintenance = 'Планове технічне обслуговування.';
    case NotifySecurityChanges = 'Важливі зміни у безпеці.';
    case NotifyNewFeatures = 'Додано нові функції на сайті.';

    public function getName(): string
    {
        return $this->value;
    }

    /**
     * Отримати всі варіанти повідомлень як масив.
     */
    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

}
