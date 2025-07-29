<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AnimeSite\Enums\UserListType;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use AnimeSite\Models\Tag;
use AnimeSite\Models\User;
use AnimeSite\Models\UserList;//Додав тому що просив гпт

class UserListSeeder extends Seeder
{
    // public function run(): void
    // {
    //     // Всі користувачі
    //     $users = User::all();

    //     foreach ($users as $user) {
    //         // Улюблені фільми
    //         $favoriteMovies = Anime::inRandomOrder()->take(rand(5, 15))->get();
    //         foreach ($favoriteMovies as $movie) {
    //             $user->userLists()->create([
    //                 'listable_id' => $movie->id,
    //                 'listable_type' => Anime::class,
    //                 'type' => UserListType::FAVORITE->value,
    //             ]);
    //         }

    //         // Улюблені персони
    //         $favoritePeople = Person::inRandomOrder()->take(rand(5, 15))->get();
    //         foreach ($favoritePeople as $person) {
    //             $user->userLists()->create([
    //                 'listable_id' => $person->id,
    //                 'listable_type' => Person::class,
    //                 'type' => UserListType::FAVORITE->value,
    //             ]);
    //         }

    //         // Улюблені теги
    //         $favoriteTags = Tag::inRandomOrder()->take(rand(5, 15))->get();
    //         foreach ($favoriteTags as $tag) {
    //             $user->userLists()->create([
    //                 'listable_id' => $tag->id,
    //                 'listable_type' => Tag::class,
    //                 'type' => UserListType::FAVORITE->value,
    //             ]);
    //         }

    //         // Переглядає епізоди
    //         $watchingEpisodes = Episode::inRandomOrder()->take(rand(5, 15))->get();
    //         foreach ($watchingEpisodes as $episode) {
    //             $user->userLists()->create([
    //                 'listable_id' => $episode->id,
    //                 'listable_type' => Episode::class,
    //                 'type' => UserListType::WATCHING->value,
    //             ]);
    //         }

    //         // Заплановані фільми
    //         /*            $plannedMovies = Anime::inRandomOrder()->take(rand(5, 15))->get();
    //                     foreach ($plannedMovies as $movie) {
    //                         $user->userLists()->create([
    //                             'listable_id' => $movie->id,
    //                             'listable_type' => Anime::class,
    //                             'type' => UserListType::PLANNED->value,
    //                         ]);
    //                     }*/
    //     }
    // }
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Улюблені фільми
            $favoriteMovies = Anime::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($favoriteMovies as $movie) {
                $this->addToList($user->id, $movie->id, Anime::class, UserListType::FAVORITE->value);
            }

            // Улюблені персони
            $favoritePeople = Person::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($favoritePeople as $person) {
                $this->addToList($user->id, $person->id, Person::class, UserListType::FAVORITE->value);
            }

            // Улюблені теги
            $favoriteTags = Tag::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($favoriteTags as $tag) {
                $this->addToList($user->id, $tag->id, Tag::class, UserListType::FAVORITE->value);
            }

            // Переглядає епізоди
            $watchingEpisodes = Episode::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($watchingEpisodes as $episode) {
                $this->addToList($user->id, $episode->id, Episode::class, UserListType::WATCHING->value);
            }

            // Заплановані фільми (якщо ти вирішиш розкоментувати)
            /*
            $plannedMovies = Anime::inRandomOrder()->take(rand(5, 15))->get();
            foreach ($plannedMovies as $movie) {
                $this->addToList($user->id, $movie->id, Anime::class, UserListType::PLANNED->value);
            }
            */
        }
    }

    private function addToList(string $userId, string $listableId, string $listableType, string $type): void
    {
        $exists = UserList::where([
            'user_id' => $userId,
            'listable_id' => $listableId,
            'listable_type' => $listableType,
        ])->exists();

        if (!$exists) {
            UserList::create([
                'user_id' => $userId,
                'listable_id' => $listableId,
                'listable_type' => $listableType,
                'type' => $type,
            ]);
        }
    }
}
