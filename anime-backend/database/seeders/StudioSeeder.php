<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AnimeSite\Models\Studio;
use Illuminate\Support\Str;

class StudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = [
            [
                'name' => 'Toei Animation',
                'description' => 'Одна з найстаріших аніме-студій Японії, відома такими культовими серіями, як "One Piece", "Dragon Ball" та "Sailor Moon".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/toei animation.png',
            ],
            [
                'name' => 'Wit Studio',
                'description' => 'Студія, що здобула славу завдяки першим трьом сезонам "Attack on Titan" та "Vinland Saga".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/Wit_studio.svg',
            ],
            [
                'name' => 'MAPPA',
                'description' => 'Сучасна студія, яка працювала над "Jujutsu Kaisen", "Chainsaw Man" та фінальними сезонами "Attack on Titan".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/mappa.jpg',
            ],
            [
                'name' => 'Studio Pierrot',
                'description' => 'Популярна студія, яка створила такі хіти, як "Naruto", "Bleach", "Tokyo Ghoul".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/Studio Pierrot.png',
            ],
            [
                'name' => 'Madhouse',
                'description' => 'Легендарна студія з великою спадщиною, відповідальна за "Death Note", "Hunter x Hunter (2011)", "One Punch Man" (1 сезон).',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/Madhouse_studio_logo.svg.png',
            ],
            [
                'name' => 'Bones',
                'description' => 'Аніме-студія, яка створила "Fullmetal Alchemist: Brotherhood", "My Hero Academia", "Mob Psycho 100".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/bones.webp',
            ],
            [
                'name' => 'Sunrise',
                'description' => 'Студія, яка стоїть за "Cowboy Bebop", "Code Geass" та численними серіями "Gundam".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/SUNRISE_animation_studios_logo.webp',
            ],
            [
                'name' => 'Gainax',
                'description' => 'Історично важлива студія, що випустила "Neon Genesis Evangelion", яка змінила аніме-індустрію.',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/gainax.webp',
            ],
            [
                'name' => 'Tatsunoko Production',
                'description' => 'Співпрацювала з Gainax над "Evangelion", також відома класичними роботами.',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/Tatsunoko_Production_logo.webp',
            ],
            [
                'name' => 'A-1 Pictures',
                'description' => 'Відповідальні за "Sword Art Online", "Erased", "Blue Exorcist", часто співпрацюють з Aniplex.',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/A-1_Pictures_Logo.svg.png',
            ],
            [
                'name' => 'Ufotable',
                'description' => 'Улюблена фанатами студія за високу якість анімації в "Demon Slayer" та серії "Fate".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/Ufotable_logo.svg.png',
            ],
            [
                'name' => 'Nippon Animation',
                'description' => 'Відповідальні за стару адаптацію "Hunter x Hunter" (1999) та класичні серії World Masterpiece Theater.',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/Nippon_Animation_Logo.png',
            ],
            [
                'name' => 'J.C.Staff',
                'description' => 'Студія, яка продовжила "One Punch Man" у 2 сезоні, а також створила "Toradora!" та "Shokugeki no Soma".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/J.C.STAFF_Logo.svg.webp',
            ],
            [
                'name' => 'CoMix Wave Films',
                'description' => 'Студія Макото Сінкая, що створила "5 сантиметрів за секунду", "Твоє ім’я", "Небо, що обіцяне".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/CoMix_Wave_Films.jpg',
            ],
            [
                'name' => 'David Production',
                'description' => 'Студія, яка прославилась "JoJo\'s Bizarre Adventure" та "Fire Force".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/david production.png',
            ],
            [
                'name' => 'OLM',
                'description' => 'Одна з найстаріших студій, відома за "Berserk" (1997) та "Pokemon".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/olm.png',
            ],
            [
                'name' => 'Studio 4°C',
                'description' => 'Відома за анімаційні експериментальні фільми та роботу над фільмами "Berserk".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/4c-divider.webp',
            ],
            [
                'name' => 'GEMBA',
                'description' => 'Менш відома студія, яка разом з Millepensee створила CGI-адаптацію "Berserk" (2016–2017).',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/Gemba.jpg',
            ],
            [
                'name' => 'Millepensee',
                'description' => 'Співпродюсер CGI-серій "Berserk", також відомі по "So I’m a Spider, So What?".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/millepense.avif',
            ],
            [
                'name' => 'Production I.G',
                'description' => 'Відомі за "Ghost in the Shell", "Psycho-Pass" та участь у створенні "Attack on Titan".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/production I G.jpg',
            ],
            [
                'name' => 'Studio Deen',
                'description' => 'Хоч і не входить до вищезгаданого списку, ця студія варта згадки за класику як "Fate/stay night" та "Rurouni Kenshin".',
                'image' => 'https://storageanimesite.blob.core.windows.net/images/studios/images/deen.jpg',
            ],
        ];

        foreach ($studios as $studio) {
            Studio::create([
                'id' => Str::ulid(),
                'slug' => Studio::generateSlug($studio['name']),
                'name' => $studio['name'],
                'description' => $studio['description'],
                'image' => $studio['image'],
                'meta_title' => Studio::makeMetaTitle($studio['name']),
                'meta_description' => Studio::makeMetaDescription($studio['description']),
                'meta_image' => $studio['image'],
            ]);
        }
    }
}
