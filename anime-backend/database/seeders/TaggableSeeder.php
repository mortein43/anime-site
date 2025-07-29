<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AnimeSite\Models\Tag;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use AnimeSite\Models\Selection;
use Illuminate\Support\Facades\DB;

class TaggableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anime = Anime::where('name', 'Наруто')->firstOrFail();

        // Назви тегів, які підходять для Naruto
        $tagNames = [
            'Екшн',
            'Пригоди',
            'Сьонен',
            'Надприродне',
            'Фентезі',
            'Школа',
        ];

        $tags = Tag::whereIn('name', $tagNames)->get();

        foreach ($tags as $tag) {
            $anime->tags()->attach($tag->id);
        }
    }
}
