<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TagSeeder::class,
            StudioSeeder::class,
            VoiceoverTeamsSeeder::class,
            PersonSeeder::class,
            AnimeSeeder::class,
            // Додаємо AnimePersonSeeder після створення аніме та персонажів
            AnimePersonSeeder::class,
            RatingSeeder::class,
            EpisodeSeeder::class,
            SelectionSeeder::class,
            UserListSeeder::class,
            CommentSeeder::class,
            CommentLikeSeeder::class,
            CommentReportSeeder::class,
            SearchHistorySeeder::class,
            WatchHistorySeeder::class,
            AchievementSeeder::class,
            AchievementUserSeeder::class,
            TariffSeeder::class,
            UserSubscriptionSeeder::class,
            PaymentSeeder::class,
            WatchPartySeeder::class,
            WatchPartyMessageSeeder::class,
        ]);
    }
}
