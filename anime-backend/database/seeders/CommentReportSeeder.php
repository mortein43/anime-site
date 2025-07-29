<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use AnimeSite\Enums\CommentReportType;
use AnimeSite\Models\Comment;
use AnimeSite\Models\CommentReport;
use AnimeSite\Models\User;

class CommentReportSeeder extends Seeder
{
    public function run(): void
    {
        $comments = Comment::inRandomOrder()->take(1)->get();
        $users = User::all();

        foreach ($comments as $comment) {
            $reportsCount = rand(1, 3);

            // $randomUsers = $users->where('id', '!=', $comment->user_id)
            //     ->random(min($reportsCount, $users->count()));
            $availableUsers = $users->where('id', '!=', $comment->user_id);
$randomUsers = $availableUsers->count() >= $reportsCount
    ? $availableUsers->random($reportsCount)
    : $availableUsers;

            // Створюємо репорти для кожного вибраного користувача
            foreach ($randomUsers as $user) {
                CommentReport::factory()
                    ->forCommentAndUser($comment, $user)  // Призначаємо конкретний коментар та користувача
                    ->withType($this->getRandomReportType())  // Встановлюємо випадковий тип репорту
                    ->create();
            }
        }
    }

    /**
     * Повертає випадковий тип репорту
     */
    private function getRandomReportType(): CommentReportType
    {
        return Arr::random(CommentReportType::cases());
    }
}
