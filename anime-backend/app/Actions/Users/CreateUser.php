<?php

namespace AnimeSite\Actions\Users;

use App\DTOs\Users\UserUpdateDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use AnimeSite\Models\User;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUser
{
    use AsAction;

    /**
     * Create a new user with the provided data.
     *
     * @param  UserUpdateDTO  $dto
     * @return User
     */
    public function handle(UserUpdateDTO $dto): User
    {
        $user = new User();

        // Set required fields
        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->password = Hash::make($dto->password);
        $user->role = $dto->role;

        // Set optional fields
        if ($dto->gender !== null) {
            $user->gender = $dto->gender;
        }

        if ($dto->avatar !== null) {
            $user->avatar = $user->handleFileUpload($dto->avatar, 'avatars');
        }

        if ($dto->backdrop !== null) {
            $user->backdrop = $user->handleFileUpload($dto->backdrop, 'backdrops');
        }

        if ($dto->description !== null) {
            $user->description = $dto->description;
        }

        if ($dto->birthday !== null) {
            $user->birthday = $dto->birthday;
        }

        // Set preferences
        $user->allow_adult = $dto->allowAdult ?? false;
        $user->is_auto_next = $dto->isAutoNext ?? true;
        $user->is_auto_play = $dto->isAutoPlay ?? true;
        $user->is_auto_skip_intro = $dto->isAutoSkipIntro ?? true;
        $user->is_private_favorites = $dto->isPrivateFavorites ?? false;

        // Set moderation status
        $user->is_banned = $dto->isBanned ?? false;

        // Notification preferences - Episodes
        $user->notify_new_episodes = $dto->notifyNewEpisodes ?? true;
        $user->notify_episode_date_changes = $dto->notifyEpisodeDateChanges ?? true;
        $user->notify_announcement_to_ongoing = $dto->notifyAnnouncementToOngoing ?? true;

        // Notification preferences - Comments
        $user->notify_comment_replies = $dto->notifyCommentReplies ?? true;
        $user->notify_comment_likes = $dto->notifyCommentLikes ?? true;

        // Notification preferences - Ratings
        $user->notify_review_replies = $dto->notifyReviewReplies ?? true;

        // Notification preferences - UserList
        $user->notify_planned_reminders = $dto->notifyPlannedReminders ?? true;

        // Notification preferences - Selections
        $user->notify_new_selections = $dto->notifyNewSelections ?? true;

        // Notification preferences - Movies
        $user->notify_status_changes = $dto->notifyStatusChanges ?? true;
        $user->notify_new_seasons = $dto->notifyNewSeasons ?? true;

        // Notification preferences - Subscription
        $user->notify_subscription_expiration = $dto->notifySubscriptionExpiration ?? true;
        $user->notify_subscription_renewal = $dto->notifySubscriptionRenewal ?? true;
        $user->notify_payment_issues = $dto->notifyPaymentIssues ?? true;
        $user->notify_tariff_changes = $dto->notifyTariffChanges ?? true;

        // Notification preferences - System
        $user->notify_site_updates = $dto->notifySiteUpdates ?? true;
        $user->notify_maintenance = $dto->notifyMaintenance ?? true;
        $user->notify_security_changes = $dto->notifySecurityChanges ?? true;
        $user->notify_new_features = $dto->notifyNewFeatures ?? true;

        $user->save();

        return $user;
    }
}
