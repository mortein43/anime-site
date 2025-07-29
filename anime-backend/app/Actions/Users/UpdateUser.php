<?php

namespace AnimeSite\Actions\Users;

use AnimeSite\DTOs\Users\UserUpdateDTO;
use AnimeSite\Models\User;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUser
{
    use AsAction;

    /**
     * Update a user with the provided data.
     *
     * @param  User  $user
     * @param  UserUpdateDTO  $dto
     * @return User
     */
    public function handle(User $user, UserUpdateDTO $dto): User
    {
        // Update basic information
        if ($dto->name !== null) {
            $user->name = $dto->name;
        }

        if ($dto->email !== null) {
            $user->email = $dto->email;
        }

        if ($dto->password !== null) {
            $user->password = Hash::make($dto->password);
        }

        if ($dto->role !== null) {
            $user->role = $dto->role;
        }

        if ($dto->gender !== null) {
            $user->gender = $dto->gender;
        }

        // Update profile information
        if ($dto->avatar !== null) {
            $user->avatar = $user->handleFileUpload($dto->avatar, 'users/avatars', $user->avatar);
        }

        if ($dto->backdrop !== null) {
            $user->backdrop = $user->handleFileUpload($dto->backdrop, 'users/backdrops', $user->backdrop);
        }

        if ($dto->description !== null) {
            $user->description = $dto->description;
        }

        if ($dto->birthday !== null) {
            $user->birthday = $dto->birthday;
        }

        // Update preferences
        if ($dto->allowAdult !== null) {
            $user->allow_adult = $dto->allowAdult;
        }

        if ($dto->isAutoNext !== null) {
            $user->is_auto_next = $dto->isAutoNext;
        }

        if ($dto->isAutoPlay !== null) {
            $user->is_auto_play = $dto->isAutoPlay;
        }

        if ($dto->isAutoSkipIntro !== null) {
            $user->is_auto_skip_intro = $dto->isAutoSkipIntro;
        }

        if ($dto->isPrivateFavorites !== null) {
            $user->is_private_favorites = $dto->isPrivateFavorites;
        }

        // Update moderation status
        if ($dto->isBanned !== null) {
            $user->is_banned = $dto->isBanned;
        }

        // Update notification preferences
        if ($dto->notifyNewEpisodes !== null) {
            $user->notify_new_episodes = $dto->notifyNewEpisodes;
        }

        if ($dto->notifyEpisodeDateChanges !== null) {
            $user->notify_episode_date_changes = $dto->notifyEpisodeDateChanges;
        }

        if ($dto->notifyAnnouncementToOngoing !== null) {
            $user->notify_announcement_to_ongoing = $dto->notifyAnnouncementToOngoing;
        }

        if ($dto->notifyCommentReplies !== null) {
            $user->notify_comment_replies = $dto->notifyCommentReplies;
        }

        if ($dto->notifyCommentLikes !== null) {
            $user->notify_comment_likes = $dto->notifyCommentLikes;
        }

        if ($dto->notifyReviewReplies !== null) {
            $user->notify_review_replies = $dto->notifyReviewReplies;
        }

        if ($dto->notifyPlannedReminders !== null) {
            $user->notify_planned_reminders = $dto->notifyPlannedReminders;
        }

        if ($dto->notifyNewSelections !== null) {
            $user->notify_new_selections = $dto->notifyNewSelections;
        }

        if ($dto->notifyStatusChanges !== null) {
            $user->notify_status_changes = $dto->notifyStatusChanges;
        }

        if ($dto->notifyNewSeasons !== null) {
            $user->notify_new_seasons = $dto->notifyNewSeasons;
        }

        if ($dto->notifySubscriptionExpiration !== null) {
            $user->notify_subscription_expiration = $dto->notifySubscriptionExpiration;
        }

        if ($dto->notifySubscriptionRenewal !== null) {
            $user->notify_subscription_renewal = $dto->notifySubscriptionRenewal;
        }

        if ($dto->notifyPaymentIssues !== null) {
            $user->notify_payment_issues = $dto->notifyPaymentIssues;
        }

        if ($dto->notifyTariffChanges !== null) {
            $user->notify_tariff_changes = $dto->notifyTariffChanges;
        }

        if ($dto->notifySiteUpdates !== null) {
            $user->notify_site_updates = $dto->notifySiteUpdates;
        }

        if ($dto->notifyMaintenance !== null) {
            $user->notify_maintenance = $dto->notifyMaintenance;
        }

        if ($dto->notifySecurityChanges !== null) {
            $user->notify_security_changes = $dto->notifySecurityChanges;
        }

        if ($dto->notifyNewFeatures !== null) {
            $user->notify_new_features = $dto->notifyNewFeatures;
        }

        $user->save();

        return $user;
    }
}
