<?php

namespace Database\Seeders;

use AnimeSite\Enums\Gender;
use AnimeSite\Enums\Role;
use Illuminate\Database\Seeder;
use AnimeSite\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Створення адміністратора
        // User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('admin'),
        //     'role' => Role::ADMIN->value,
        //     'avatar' => 'https://storageanimesite.blob.core.windows.net/images/users/avatars/avanar.jpg',
        //     'description' => 'Системний адміністратор сайту',
        //     'gender' => Gender::MALE,
        //     'birthday' => '1990-01-01',
        //     'allow_adult' => true,
        //     'last_seen_at' => now(),
        //     'is_auto_next' => true,
        //     'is_auto_play' => true,
        //     'is_auto_skip_intro' => true,
        //     'is_private_favorites' => false,
        //     'is_banned' => false,
        // ]);

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => Role::ADMIN->value,
            'avatar' => 'https://storageanimesite.blob.core.windows.net/images/users/avatars/avanar.jpg',
            'description' => 'Системний адміністратор сайту',
            'gender' => Gender::MALE,
            'birthday' => '1990-01-01',
            'allow_adult' => true,
            'last_seen_at' => now(),
            'is_auto_next' => true,
            'is_auto_play' => true,
            'is_auto_skip_intro' => true,
            'is_private_favorites' => false,
            'is_banned' => false,
        ]);

        // Масив тестових користувачів
        $users = [
            ['username' => 'naruto_hero',      'name' => 'Naruto',      'gender' => Gender::MALE,   'birthday' => '1999-10-10', 'avatar' =>'https://storageanimesite.blob.core.windows.net/images/users/avatars/avanar.jpg'],
            ['username' => 'sakura_medic',     'name' => 'Sakura',      'gender' => Gender::FEMALE, 'birthday' => '2000-03-28', 'avatar' =>'https://storageanimesite.blob.core.windows.net/images/users/avatars/avasak.jpg'],
            // ... інші користувачі
        ];

        // Створення тестових користувачів
        // foreach ($users as $u) {
        //     User::create([
        //         'name' => $u['username'],
        //         'email' => $u['username'] . '@mail.com',
        //         'password' => Hash::make('password'),
        //         'role' => Role::USER->value,
        //         'avatar' => $u['avatar'],
        //         'description' => 'Я фанат аніме і люблю персонажа ' . $u['name'],
        //         'gender' => $u['gender'],
        //         'birthday' => $u['birthday'],
        //         'allow_adult' => false,
        //         'last_seen_at' => now()->subDays(rand(1, 30)),
        //         'is_auto_next' => true,
        //         'is_auto_play' => false,
        //         'is_auto_skip_intro' => true,
        //         'is_private_favorites' => false,
        //         'is_banned' => false,
        //     ]);
        // }
        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['username'] . '@mail.com'],
                [
                'name' => $u['username'],
                'email' => $u['username'] . '@mail.com',
                'password' => Hash::make('password'),
                'role' => Role::USER->value,
                'avatar' => $u['avatar'],
                'description' => 'Я фанат аніме і люблю персонажа ' . $u['name'],
                'gender' => $u['gender'],
                'birthday' => $u['birthday'],
                'allow_adult' => false,
                'last_seen_at' => now()->subDays(rand(1, 30)),
                'is_auto_next' => true,
                'is_auto_play' => false,
                'is_auto_skip_intro' => true,
                'is_private_favorites' => false,
                'is_banned' => false,
            ]);
        }
    }
}
