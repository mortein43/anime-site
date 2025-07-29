<?php

namespace Database\Seeders;

use AnimeSite\Models\VoiceoverTeam;
use Illuminate\Database\Seeder;
use AnimeSite\Enums\Kind;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Anime;

class EpisodeSeeder extends Seeder
{
    public function run(): void
    {
        $anime = Anime::where('name', 'like', '%Наруто')->first();

        if (!$anime) {
            $this->command->warn('Naruto anime not found. Skipping EpisodeSeeder.');
            return;
        }

        $episodes = [
            [
                'number' => 1,
                'name' => 'Узумакі Наруто',
                'description' => 'Початок історії Наруто — хлопця, який прагне стати Хокаге.',
                'air_date' => '2002-10-03',
                'duration' => 23,
                'is_filler' => false,
                'pictures' => json_encode([
                    'https://storageanimesite.blob.core.windows.net/images/episode/pictures/scene.jpg',
                ]),
            ],
            [
                'number' => 2,
                'name' => 'Мій імпульсивний суперник!',
                'description' => 'Наруто знайомиться з Саске та Сакурою в новоствореній команді 7.',
                'air_date' => '2002-10-10',
                'duration' => 23,
                'is_filler' => false,
                'pictures' => json_encode([
                    'https://storageanimesite.blob.core.windows.net/images/episode/pictures/scene_1.jpg',
                ]),
            ],
            [
                'number' => 3,
                'name' => 'Саске і Сакура: Подвійна неприємність!',
                'description' => 'Команда 7 розпочинає тренування з новим сенсеєм — Какаші.',
                'air_date' => '2002-10-17',
                'duration' => 23,
                'is_filler' => false,
                'pictures' => json_encode([
                    'https://storageanimesite.blob.core.windows.net/images/episode/pictures/episode_1.jpg',
                ]),
            ],
            [
                'number' => 4,
                'name' => 'Розбий дзвоник!',
                'description' => 'Перше випробування від Какаші: випробування з дзвониками.',
                'air_date' => '2002-10-24',
                'duration' => 23,
                'is_filler' => false,
                'pictures' => json_encode([
                    'https://storageanimesite.blob.core.windows.net/images/episode/pictures/episode_2.jpg',
                ]),
            ],
            [
                'number' => 5,
                'name' => 'Не заважай! Місія рангy D!',
                'description' => 'Команда 7 вирушає на першу справжню місію.',
                'air_date' => '2002-10-31',
                'duration' => 23,
                'is_filler' => false,
                'pictures' => json_encode([
                    'https://storageanimesite.blob.core.windows.net/images/episode/pictures/episode_3.jpg',
                ]),
            ],
        ];

        // foreach ($episodes as $ep) {
        //     $number = $ep['number'];
        //     $slug = Episode::generateSlug($ep['name']);

        //     Episode::create([
        //         'anime_id' => $anime->id,
        //         'number' => $number,
        //         'slug' => $slug,
        //         'name' => $ep['name'],
        //         'description' => $ep['description'],
        //         'duration' => $ep['duration'],
        //         'air_date' => $ep['air_date'],
        //         'is_filler' => $ep['is_filler'],
        //         'pictures' => $ep['pictures'],
        //         'video_players' => json_encode([
        //             [
        //                 'url' => 'https://player.example.com/naruto/ep' . $number,
        //                 'voiceover_team' => VoiceoverTeam::inRandomOrder()->value('name'),
        //                 'quality' => '1080p',
        //                 'lang' => 'uk',
        //             ],
        //         ]),
        //         'meta_title' => $ep['name'] . ' | Naruto',
        //         'meta_description' => $ep['description'],
        //         'meta_image' => 'https://static.wikia.nocookie.net/naruto/images/1/12/Naruto_Episode_' . $number . '.png',
        //     ]);
        // }
        foreach ($episodes as $ep) {
    $number = $ep['number'];
    $slug = Episode::generateSlug($ep['name']);

    // 🛡 Перевірка на дублікати
    if (!Episode::where('anime_id', $anime->id)->where('number', $number)->exists()) {
        Episode::create([
            'anime_id' => $anime->id,
            'number' => $number,
            'slug' => $slug,
            'name' => $ep['name'],
            'description' => $ep['description'],
            'duration' => $ep['duration'],
            'air_date' => $ep['air_date'],
            'is_filler' => $ep['is_filler'],
            'pictures' => $ep['pictures'],
            'video_players' => json_encode([
                [
                    'url' => 'https://player.example.com/naruto/ep' . $number,
                    'voiceover_team' => VoiceoverTeam::inRandomOrder()->value('name'),
                    'quality' => '1080p',
                    'lang' => 'uk',
                ],
            ]),
            'meta_title' => $ep['name'] . ' | Naruto',
            'meta_description' => $ep['description'],
            'meta_image' => 'https://static.wikia.nocookie.net/naruto/images/1/12/Naruto_Episode_' . $number . '.png',
        ]);
    } else {
        $this->command->warn("Епізод №{$number} вже існує — пропущено.");
    }
}
    }
}
