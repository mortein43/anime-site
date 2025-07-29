<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AnimeSite\Models\Comment;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Selection;
use AnimeSite\Models\User;

class CommentSeeder extends Seeder
{
        // Знаходимо аніме "Naruto"
        public function run(): void
    {
        $naruto = Anime::where('name', 'like', '%Наруто')->first();

        if (!$naruto) {
            $this->command->warn('Naruto anime not found. Skipping CommentSeeder.');
            return;
        }

        $users = User::inRandomOrder()->take(5)->get();

        $comments = [
            'Naruto — це справжня класика. Переглядав уже тричі!',
            'Момент з Джираєю — один із найемоційніших у всьому аніме 😢',
            'Дуже сильне зростання персонажів. Особливо у Shippuden.',
            'Музика, бійки, історія — все на високому рівні!',
            'Анімовані арки про Учіха просто вибух мозку!',
        ];

        foreach ($comments as $i => $body) {
            Comment::create([
                'commentable_type' => Anime::class,
                'commentable_id' => $naruto->id,
                'user_id' => $users[$i % $users->count()]->id,
                'is_spoiler' => false,
                'is_approved' => true,
                'body' => $body,
            ]);
        }
    }

}
