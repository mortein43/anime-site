<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use Illuminate\Support\Facades\DB;

class AnimePersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anime = Anime::where('name', 'Наруто')->firstOrFail();

        // Дані для звʼязку
        $characters = [
            [
                'character' => 'Наруто Удзумаки',
                'voice' => 'Джунко Такеучі',
            ],
            [
                'character' => 'Саске Учіха',
                'voice' => null,
            ],
            [
                'character' => 'Сакура Харіно',
                'voice' => 'Маая Сакамото',
            ],
            [
                'character' => 'Какаші Хатаке',
                'voice' => 'Казухіко Іноуе',
            ],
        ];

        // foreach ($characters as $data) {
        //     $character = Person::where('name', $data['character'])->first();
        //     $voiceActor = $data['voice'] ? Person::where('name', $data['voice'])->first() : null;

        //     if ($character) {
        //         DB::table('anime_person')->insert([
        //             'anime_id' => $anime->id,
        //             'person_id' => $character->id,
        //             'voice_person_id' => $voiceActor?->id,
        //             'character_name' => $character->name,
        //         ]);
        //     }
        // }
        foreach ($characters as $data) {
            $character = Person::where('name', $data['character'])->first();
            $voiceActor = $data['voice'] ? Person::where('name', $data['voice'])->first() : null;

            if ($character && !DB::table('anime_person')
                ->where('anime_id', $anime->id)
                ->where('person_id', $character->id)
                ->exists()) {

                DB::table('anime_person')->insert([
                    'anime_id' => $anime->id,
                    'person_id' => $character->id,
                    'voice_person_id' => $voiceActor?->id,
                    'character_name' => $character->name,
                ]);
            }
        }
    }


}
