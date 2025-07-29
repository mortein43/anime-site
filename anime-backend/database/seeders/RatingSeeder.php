<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AnimeSite\Models\Rating;
use AnimeSite\Models\Anime;
use AnimeSite\Models\User;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        // Спочатку створюємо рейтинги для Naruto
        $this->createNarutoRatings();
    }

    private function createNarutoRatings(): void
    {
        $naruto = Anime::where('name', 'Наруто')->first();

        if (!$naruto) {
            return;
        }

        // Отримуємо користувачів Naruto
        $users = User::whereIn('email', [
            'naruto.fan.2024@example.com',
            'sasuke.lover@example.com',
            'sakura.chan@example.com',
            'kakashi.sensei@example.com',
            'hokage.dreamer@example.com'
        ])->get();

        if ($users->count() === 0) {
            return;
        }

        $ratings = [
            [
                'user' => 0,
                'score' => 9,
                'review' => 'Amazing anime! Naruto\'s journey is incredible and inspiring. The character development throughout the series is phenomenal.'
            ],
            [
                'user' => 1,
                'score' => 8,
                'review' => 'Great story and character development. Some filler episodes though, but overall an excellent series.'
            ],
            [
                'user' => 2,
                'score' => 10,
                'review' => 'Perfect! This anime changed my life. Believe it! The themes of friendship and never giving up are so powerful.'
            ],
            [
                'user' => 3,
                'score' => 7,
                'review' => 'Good anime but too many flashbacks and slow pacing sometimes. Still worth watching for the story.'
            ],
            [
                'user' => 4,
                'score' => 9,
                'review' => 'Excellent world-building and ninja techniques. Love the fights and the emotional moments!'
            ],
        ];

        foreach ($ratings as $index => $ratingData) {
            if (isset($users[$ratingData['user']])) {
                Rating::firstOrCreate([
                    'user_id' => $users[$ratingData['user']]->id,
                    'anime_id' => $naruto->id,
                ], [
                    'number' => $ratingData['score'],
                    'review' => $ratingData['review'],
                ]);
            }
        }
    }
}
