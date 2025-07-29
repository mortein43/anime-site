<?php

namespace AnimeSite\DTOs\Users;

class UserSettingsDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public bool $allowAdult,
        public bool $isAutoNext,
        public bool $isAutoPlay,
        public bool $isAutoSkipIntro,
        public bool $isPrivateFavorites,

        // Notify group
        public bool $notifyNewEpisodes,
        public bool $notifyEpisodeDateChanges,
        public bool $notifyAnnouncementToOngoing,
        public bool $notifyCommentReplies,
        public bool $notifyCommentLikes,
        public bool $notifyReviewReplies,
        public bool $notifyPlannedReminders,
        public bool $notifyNewSelections,
        public bool $notifyStatusChanges,
        public bool $notifyNewSeasons,
        public bool $notifySubscriptionExpiration,
        public bool $notifySubscriptionRenewal,
        public bool $notifyPaymentIssues,
        public bool $notifyTariffChanges,
        public bool $notifySiteUpdates,
        public bool $notifyMaintenance,
        public bool $notifySecurityChanges,
        public bool $notifyNewFeatures,
    ) {}

    public static function from(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            allowAdult: $user->allow_adult,
            isAutoNext: $user->is_auto_next,
            isAutoPlay: $user->is_auto_play,
            isAutoSkipIntro: $user->is_auto_skip_intro,
            isPrivateFavorites: $user->is_private_favorites,

            notifyNewEpisodes: $user->notify_new_episodes,
            notifyEpisodeDateChanges: $user->notify_episode_date_changes,
            notifyAnnouncementToOngoing: $user->notify_announcement_to_ongoing,
            notifyCommentReplies: $user->notify_comment_replies,
            notifyCommentLikes: $user->notify_comment_likes,
            notifyReviewReplies: $user->notify_review_replies,
            notifyPlannedReminders: $user->notify_planned_reminders,
            notifyNewSelections: $user->notify_new_selections,
            notifyStatusChanges: $user->notify_status_changes,
            notifyNewSeasons: $user->notify_new_seasons,
            notifySubscriptionExpiration: $user->notify_subscription_expiration,
            notifySubscriptionRenewal: $user->notify_subscription_renewal,
            notifyPaymentIssues: $user->notify_payment_issues,
            notifyTariffChanges: $user->notify_tariff_changes,
            notifySiteUpdates: $user->notify_site_updates,
            notifyMaintenance: $user->notify_maintenance,
            notifySecurityChanges: $user->notify_security_changes,
            notifyNewFeatures: $user->notify_new_features,
        );
    }
}

