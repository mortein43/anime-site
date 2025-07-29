<?php

namespace AnimeSite\DTOs\Users;

use AnimeSite\Enums\Gender;
use AnimeSite\Enums\Role;
use AnimeSite\DTOs\BaseDTO;
use Illuminate\Http\Request;

class UserUpdateDTO extends BaseDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?Role $role = null,
        public readonly ?Gender $gender = null,
        public readonly ?string $avatar = null,
        public readonly ?string $backdrop = null,
        public readonly ?string $description = null,
        public readonly ?string $birthday = null,
        public readonly ?bool $allowAdult = null,
        public readonly ?bool $isAutoNext = null,
        public readonly ?bool $isAutoPlay = null,
        public readonly ?bool $isAutoSkipIntro = null,
        public readonly ?bool $isPrivateFavorites = null,
        public readonly ?bool $isBanned = null,

        // Notification preferences
        public readonly ?bool $notifyNewEpisodes = null,
        public readonly ?bool $notifyEpisodeDateChanges = null,
        public readonly ?bool $notifyAnnouncementToOngoing = null,
        public readonly ?bool $notifyCommentReplies = null,
        public readonly ?bool $notifyCommentLikes = null,
        public readonly ?bool $notifyReviewReplies = null,
        public readonly ?bool $notifyPlannedReminders = null,
        public readonly ?bool $notifyNewSelections = null,
        public readonly ?bool $notifyStatusChanges = null,
        public readonly ?bool $notifyNewSeasons = null,
        public readonly ?bool $notifySubscriptionExpiration = null,
        public readonly ?bool $notifySubscriptionRenewal = null,
        public readonly ?bool $notifyPaymentIssues = null,
        public readonly ?bool $notifyTariffChanges = null,
        public readonly ?bool $notifySiteUpdates = null,
        public readonly ?bool $notifyMaintenance = null,
        public readonly ?bool $notifySecurityChanges = null,
        public readonly ?bool $notifyNewFeatures = null,
    ) {
    }

    public static function fields(): array
    {
        return [
            'name',
            'email',
            'password',
            'role',
            'gender',
            'avatar',
            'backdrop',
            'description',
            'birthday',
            'allow_adult' => 'allowAdult',
            'is_auto_next' => 'isAutoNext',
            'is_auto_play' => 'isAutoPlay',
            'is_auto_skip_intro' => 'isAutoSkipIntro',
            'is_private_favorites' => 'isPrivateFavorites',
            'is_banned' => 'isBanned',

            // Notifications
            'notify_new_episodes' => 'notifyNewEpisodes',
            'notify_episode_date_changes' => 'notifyEpisodeDateChanges',
            'notify_announcement_to_ongoing' => 'notifyAnnouncementToOngoing',
            'notify_comment_replies' => 'notifyCommentReplies',
            'notify_comment_likes' => 'notifyCommentLikes',
            'notify_review_replies' => 'notifyReviewReplies',
            'notify_planned_reminders' => 'notifyPlannedReminders',
            'notify_new_selections' => 'notifyNewSelections',
            'notify_status_changes' => 'notifyStatusChanges',
            'notify_new_seasons' => 'notifyNewSeasons',
            'notify_subscription_expiration' => 'notifySubscriptionExpiration',
            'notify_subscription_renewal' => 'notifySubscriptionRenewal',
            'notify_payment_issues' => 'notifyPaymentIssues',
            'notify_tariff_changes' => 'notifyTariffChanges',
            'notify_site_updates' => 'notifySiteUpdates',
            'notify_maintenance' => 'notifyMaintenance',
            'notify_security_changes' => 'notifySecurityChanges',
            'notify_new_features' => 'notifyNewFeatures',
        ];
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
            role: $request->has('role') ? Role::from($request->input('role')) : null,
            gender: $request->has('gender') ? Gender::from($request->input('gender')) : null,
            avatar: $request->input('avatar'),
            backdrop: $request->input('backdrop'),
            description: $request->input('description'),
            birthday: $request->input('birthday'),
            allowAdult: $request->boolean('allow_adult', null),
            isAutoNext: $request->boolean('is_auto_next', null),
            isAutoPlay: $request->boolean('is_auto_play', null),
            isAutoSkipIntro: $request->boolean('is_auto_skip_intro', null),
            isPrivateFavorites: $request->boolean('is_private_favorites', null),
            isBanned: $request->boolean('is_banned', null),

            // Notification preferences
            notifyNewEpisodes: $request->boolean('notify_new_episodes', null),
            notifyEpisodeDateChanges: $request->boolean('notify_episode_date_changes', null),
            notifyAnnouncementToOngoing: $request->boolean('notify_announcement_to_ongoing', null),
            notifyCommentReplies: $request->boolean('notify_comment_replies', null),
            notifyCommentLikes: $request->boolean('notify_comment_likes', null),
            notifyReviewReplies: $request->boolean('notify_review_replies', null),
            notifyPlannedReminders: $request->boolean('notify_planned_reminders', null),
            notifyNewSelections: $request->boolean('notify_new_selections', null),
            notifyStatusChanges: $request->boolean('notify_status_changes', null),
            notifyNewSeasons: $request->boolean('notify_new_seasons', null),
            notifySubscriptionExpiration: $request->boolean('notify_subscription_expiration', null),
            notifySubscriptionRenewal: $request->boolean('notify_subscription_renewal', null),
            notifyPaymentIssues: $request->boolean('notify_payment_issues', null),
            notifyTariffChanges: $request->boolean('notify_tariff_changes', null),
            notifySiteUpdates: $request->boolean('notify_site_updates', null),
            notifyMaintenance: $request->boolean('notify_maintenance', null),
            notifySecurityChanges: $request->boolean('notify_security_changes', null),
            notifyNewFeatures: $request->boolean('notify_new_features', null),
        );
    }
}
