<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AnimeSite\Models\Person;
use AnimeSite\Enums\PersonType;
use AnimeSite\Enums\Gender;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $people = [
            // Персонажі Naruto
            [
                'name' => 'Наруто Удзумакі',
                'original_name' => 'Naruto Uzumaki',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/people/images/naruto_uzumaki.jpg',
                'description' => 'Головний герой серіалу "Naruto", ніндзя з Конохи.',
                'birthday' => '1999-10-10',
                'birthplace' => 'Коніха',
                'type' => PersonType::CHARACTER,
                'gender' => Gender::MALE,
            ],
            [
                'name' => 'Саске Учіха',
                'original_name' => 'Sasuke Uchiha',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/people/images/sasuke.jpg',
                'description' => 'Член клану Учіха, колишній товариш Наруто.',
                'birthday' => '1999-07-23',
                'birthplace' => 'Коніха',
                'type' => PersonType::CHARACTER,
                'gender' => Gender::MALE,
            ],
            [
                'name' => 'Сакура Харіно',
                'original_name' => 'Sakura Haruno',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/people/images/sakura.jpg',
                'description' => 'Медична ніндзя, член команди 7.',
                'birthday' => '2000-03-28',
                'birthplace' => 'Коніха',
                'type' => PersonType::CHARACTER,
                'gender' => Gender::FEMALE,
            ],
            [
                'name' => 'Какаші Хатаке',
                'original_name' => 'Kakashi Hatake',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/people/images/kakashi.jpg',
                'description' => 'Наставник команди 7, талановитий ніндзя.',
                'birthday' => '1976-09-15',
                'birthplace' => 'Коніха',
                'type' => PersonType::CHARACTER,
                'gender' => Gender::MALE,
            ],
            // Люди (сейю, режисери, композитори)
            [
                'name' => 'Маая Сакамото',
                'original_name' => 'Maaya Sakamoto',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/people/images/1.jpg',
                'description' => 'Японська акторка озвучення, озвучила Сакуру Харіно у "Naruto".',
                'birthday' => '1980-03-31',
                'birthplace' => 'Токіо, Японія',
                'type' => PersonType::VOICE_ACTOR,
                'gender' => Gender::FEMALE,
            ],
            [
                'name' => 'Джунко Такеучі',
                'original_name' => 'Junko Takeuchi',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/people/images/2.jpg',
                'description' => 'Японська акторка озвучення, відома як голос Наруто Удзумаки.',
                'birthday' => '1972-04-05',
                'birthplace' => 'Токіо, Японія',
                'type' => PersonType::VOICE_ACTOR,
                'gender' => Gender::FEMALE,
            ],
            [
                'name' => 'Казухіко Іноуе',
                'original_name' => 'Kazuhiko Inoue',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/people/images/3.jpg',
                'description' => 'Японський актор озвучення, озвучив Какаші Хатаке у "Naruto".',
                'birthday' => '1954-08-05',
                'birthplace' => 'Токіо, Японія',
                'type' => PersonType::VOICE_ACTOR,
                'gender' => Gender::MALE,
            ],
            [
                'name' => 'Хаято Дате',
                'original_name' => 'Hayato Date',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/people/images/4.jpg',
                'description' => 'Режисер аніме "Naruto".',
                'birthday' => null,
                'birthplace' => null,
                'type' => PersonType::DIRECTOR,
                'gender' => Gender::MALE,
            ],
        ];

        foreach ($people as $data) {
            Person::create([
                'slug' => Person::generateSlug($data['name']),
                'name' => $data['name'],
                'original_name' => $data['original_name'] ?? null,
                'image' => $data['image'] ?? null,
                'description' => $data['description'] ?? null,
                'birthday' => $data['birthday'] ?? null,
                'birthplace' => $data['birthplace'] ?? null,
                'type' => $data['type'],
                'gender' => $data['gender'] ?? null,
                'meta_title' => Person::makeMetaTitle($data['name']),
                'meta_description' => Person::makeMetaDescription($data['description'] ?? ''),
                'meta_image' => $data['image'] ?? null,
            ]);
        }
    }
}
