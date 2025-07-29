<?php

namespace AnimeSite\Console\Commands;

use AnimeSite\Models\Anime;
use AnimeSite\Models\User;
use AnimeSite\Notifications\NotifyPlannedReminders;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendPlannedAnimeReminders extends Command
{
    protected $signature = 'notify:planned-reminders';
    protected $description = 'Надіслати нагадування користувачам про аніме в списку В планах';

    public function handle()
    {
        // Вибираємо всіх користувачів з планованими аніме
        $userIds = DB::table('user_lists')
            ->where('listable_type', Anime::class)
            ->where('type', 'planned')
            ->distinct()
            ->pluck('user_id');

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (!$user || ! $user->notify_planned_anime_reminders) {
                continue;
            }

            // Випадкове аніме зі списку planned для користувача
            $animeId = DB::table('user_lists')
                ->where('user_id', $userId)
                ->where('listable_type', Anime::class)
                ->where('type', 'planned')
                ->inRandomOrder()
                ->value('listable_id');

            $anime = Anime::find($animeId);
            if (!$anime) {
                continue;
            }

            $user->notify(new NotifyPlannedReminders($anime));
        }

        $this->info('Нагадування для планованих аніме відправлені випадковим користувачам.');
    }
}
