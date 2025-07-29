<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\Period;
use AnimeSite\Enums\RestrictedRating;
use AnimeSite\Enums\Source;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Studio;
use AnimeSite\Models\Tag;
use AnimeSite\Models\Person;
use AnimeSite\Models\User;


class AnimeSeeder extends Seeder
{
    public function run(): void
    {

        //Anime::factory(20)->create();
        $studios = [
            'Pierrot' => Studio::where('name', 'Studio Pierrot')->first(),
            'MAPPA' => Studio::where('name', 'MAPPA')->first(),
            'ufotable'  => Studio::where('name', 'Ufotable')->first(),
            'Madhouse' => Studio::where('name', 'Madhouse')->first(),
            'Bones'  => Studio::where('name', 'Bones')->first(),
            'Sunrise'  => Studio::where('name', 'Sunrise')->first(),
            'Gainax' => Studio::where('name', 'Gainax')->first(),
            'A-1 Pictures'  => Studio::where('name', 'A-1 Pictures')->first(),
            'David Production'  => Studio::where('name', 'David Production')->first(),
            'OLM' => Studio::where('name', 'OLM')->first(),
            'Wit Studio' => Studio::where('name', 'Wit Studio')->first(),
            'Toei Animation' => Studio::where('name', 'Toei Animation')->first(),
        ];
        $animesIds = DB::table('animes')->pluck('id', 'slug');

        $animes = [
            [
                'slug' => Anime::generateSlug('Наруто'),
                'name' => 'Наруто',
                'description' => 'Історія молодого ніндзя Наруто Узумаки, який мріє стати найсильнішим лідером своєї села — Хокаге.',
                //'image_name' => 'https://upload.wikimedia.org/wikipedia/en/9/94/NarutoCoverTankobon1.jpg',
                'aliases' => json_encode(['ナルト', 'Наруто']),
                'studio_id' => $studios['Pierrot']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/naruto.jpg',
                'duration' => 23,
                'episodes_count' => 220,
                'first_air_date' => '2002-10-03',
                'last_air_date' => '2007-02-08',
                'imdb_score' => 8.3,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '20']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/9/94/NarutoCoverTankobon1.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=6t9z1Yr-Rrc'],
                ]),

                'related' => json_encode([
                    ['id' => $animesIds['naruto-shippuden'] ?? 'ulid_placeholder', 'type' => 'season'],
                    ['id' => $animesIds['naruto-movie'] ?? 'ulid_placeholder', 'type' => 'movie'],
                ]),

                'is_published' => true,
                'meta_title' => 'Naruto - Японське аніме про ніндзя',
                'meta_description' => 'Дивіться Naruto - класичне аніме про пригоди молодого ніндзя та його друзів.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/naruto.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'autumn',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Наруто: Шіппуден'),
                'name' => 'Наруто: Шіппуден',
                'description' => 'Продовження пригод Наруто Узумаки після дворічної відсутності. Сильніші вороги та нові друзі.',
                //'image_name' => 'https://upload.wikimedia.org/wikipedia/en/6/60/Naruto_Shippuden_Logo.png',
                'aliases' => json_encode(['ナルト 疾風伝']),
                'studio_id' => $studios['Pierrot']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/naruto shippuden.webp',
                'duration' => 23,
                'episodes_count' => 500,
                'first_air_date' => '2007-02-15',
                'last_air_date' => '2017-03-23',
                'imdb_score' => 8.6,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '1735']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/6/60/Naruto_Shippuden_Logo.png'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=RG8IgE5xLX4'],
                ]),

                'related' => json_encode([
                    ['id' => $animesIds['naruto'] ?? 'ulid_placeholder', 'type' => 'season'],
                ]),

                'is_published' => true,
                'meta_title' => 'Naruto Shippuden - Продовження історії Наруто',
                'meta_description' => 'Продовжуйте слідкувати за Наруто у нових пригодах і битвах.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/naruto shippuden.webp',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'winter',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => Anime::generateSlug('Ван Піс'),
                'name' => 'Ван Піс',
                'description' => 'Історія молодого пірата Луффі, який шукає легендарний скарб "One Piece", щоб стати Королем Піратів.',
                //'image_name' => 'https://upload.wikimedia.org/wikipedia/en/2/29/OnePieceCoverVolume1.jpg',
                'aliases' => json_encode(['ワンピース']),
                'studio_id' => $studios['Toei Animation']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/one piece.jpg',
                'duration' => 24,
                'episodes_count' => 1100,
                'first_air_date' => '1999-10-20',
                'last_air_date' => null,
                'imdb_score' => 8.9,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '21']]),
                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/2/29/OnePieceCoverVolume1.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=KzH1iaxU3bA'],
                ]),
                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'One Piece — Легендарне піратське аніме',
                'meta_description' => 'Стань частиною пригод Луффі та його екіпажу в пошуках One Piece!',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/one piece.jpg',
                'kind' => 'tv_series',
                'status' => 'ongoing',
                'period' => 'autumn',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Бліч'),
                'name' => 'Бліч',
                'description' => 'Життя Ічіґо Куросакі змінюється, коли він отримує силу Жнеця душ, борючись із порожніми та рятуючи душі.',
                //'image_name' => 'https://upload.wikimedia.org/wikipedia/en/9/9f/Bleachanime.png',
                'aliases' => json_encode(['ブリーチ']),
                'studio_id' => $studios['Pierrot']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/bleach.jpg',
                'duration' => 24,
                'episodes_count' => 366,
                'first_air_date' => '2004-10-05',
                'last_air_date' => '2012-03-27',
                'imdb_score' => 8.1,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '269']]),
                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/9/9f/Bleachanime.png'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=HRhRyIalbgY'],
                ]),
                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Bleach — Аніме про Жнеців душ',
                'meta_description' => 'Дивіться Bleach — захоплива історія про боротьбу з потойбічними істотами.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/bleach.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'autumn',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Атака Титанів'),
                'name' => 'Атака Титанів',
                'description' => 'У світі, де людство живе за стінами, що захищають від гігантів, починається боротьба за виживання.',
                //'image_name' => 'https://upload.wikimedia.org/wikipedia/en/7/70/Attack_on_Titan_season_1_DVD.jpg',
                'aliases' => json_encode(['進撃の巨人', 'Shingeki no Kyojin']),
                'studio_id' => $studios['MAPPA']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/ataka-titanov.jpg',
                'duration' => 24,
                'episodes_count' => 87,
                'first_air_date' => '2013-04-06',
                'last_air_date' => '2023-11-04',
                'imdb_score' => 9.1,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '16498']]),
                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/7/70/Attack_on_Titan_season_1_DVD.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=MGRm4IzK1SQ'],
                ]),
                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Attack on Titan — Трилер про гігантів',
                'meta_description' => 'Епічна історія про боротьбу людства проти гігантів. Дивіться Attack on Titan!',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/ataka-titanov.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'spring',
                'restricted_rating' => 'r',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Клинок, що знищує демонів'),
                'name' => 'Клинок, що знищує демонів',
                'description' => 'Юний Танджіро бореться з демонами, щоб врятувати свою сестру та помститися за родину.',
                //'image_name' => 'https://upload.wikimedia.org/wikipedia/en/f/fb/Kimetsu_no_Yaiba_volume_1_cover.jpg',
                'aliases' => json_encode(['鬼滅の刃', 'Kimetsu no Yaiba']),
                'studio_id' => $studios['ufotable']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/demon slayer.webp',
                'duration' => 24,
                'episodes_count' => 55,
                'first_air_date' => '2019-04-06',
                'last_air_date' => null,
                'imdb_score' => 8.7,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '38000']]),
                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/f/fb/Kimetsu_no_Yaiba_volume_1_cover.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=VQGCKyvzIM4'],
                ]),
                'related' => json_encode([]),
                'is_published' => true,
                'meta_title' => 'Demon Slayer — Аніме про демонів та мечі',
                'meta_description' => 'Захопливе аніме з красивою анімацією та драматичним сюжетом. Дивіться Kimetsu no Yaiba!',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/demon slayer.webp',
                'kind' => 'tv_series',
                'status' => 'ongoing',
                'period' => 'spring',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Зошит Смерті'),
                'name' => 'Зошит Смерті',
                'description' => 'Старшокласник Лайт Яґамі знаходить зошит, який дозволяє вбивати будь-кого, знаючи його імʼя та обличчя.',
                'aliases' => json_encode(['デスノート', 'Зошит смерті']),
                'studio_id' => $studios['Madhouse']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/death note.jpg',
                'duration' => 23,
                'episodes_count' => 37,
                'first_air_date' => '2006-10-04',
                'last_air_date' => '2007-06-27',
                'imdb_score' => 9.0,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '1535']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/6/6f/Death_Note_Vol_1.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=NlJZ-YgAt-c'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Death Note — Психологічний трилер',
                'meta_description' => 'Аніме про хлопця, який отримує силу вбивати за допомогою зошита. Чи зможе він змінити світ?',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/death note.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'autumn',
                'restricted_rating' => 'r',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Сталевий Алхімік: Братство'),
                'name' => 'Сталевий Алхімік: Братство',
                'description' => 'Дві брати-алхіміки шукають філософський камінь, щоб повернути втрачені тіла після невдалого експерименту.',
                'aliases' => json_encode(['鋼の錬金術師 FULLMETAL ALCHEMIST', 'Сталевий алхімік: Братство']),
                'studio_id' => $studios['Bones']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/alchemist.jpg',
                'duration' => 24,
                'episodes_count' => 64,
                'first_air_date' => '2009-04-05',
                'last_air_date' => '2010-07-04',
                'imdb_score' => 9.1,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '5114']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/6/6f/Fullmetal_Alchemist_Brotherhood_DVD_volume_1_cover.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=O8G7P3SmmP0'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Fullmetal Alchemist: Brotherhood — Епічна історія братерства',
                'meta_description' => 'Аніме про братів, які борються за відновлення втраченого, використовуючи алхімію та силу дружби.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/alchemist.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'spring',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],


            [
                'slug' => Anime::generateSlug('Майстри Меча Онлайн'),
                'name' => 'Майстри Меча Онлайн',
                'description' => 'Гравці застрягли у віртуальній MMORPG, де смерть у грі означає смерть у реальному житті.',
                'aliases' => json_encode(['ソードアート・オンライン', 'Майстри меча онлайн']),
                'studio_id' => $studios['A-1 Pictures']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/sao.jpg',
                'duration' => 24,
                'episodes_count' => 25,
                'first_air_date' => '2012-07-08',
                'last_air_date' => '2012-12-23',
                'imdb_score' => 7.6,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '11757']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/4/4b/Sword_Art_Online_Volume_1_DVD.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=AOeY-nDp7hI'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Sword Art Online — Пригоди у віртуальному світі',
                'meta_description' => 'Дивіться історію про гравців, які опинилися у пастці віртуальної реальності. Майстри меча онлайн — це захопливо!',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/sao.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'summer',
                'restricted_rating' => 'pg_13',
                'source' => 'light_novel',
                'created_at' => now(),
                'updated_at' => now(),
            ],




            [
                'slug' => Anime::generateSlug('Мисливець х Мисливець'),
                'name' => 'Мисливець х Мисливець',
                'description' => 'Історія хлопчика Ґона, який стає мисливцем, щоб знайти свого зниклого батька і досліджувати дивовижний світ. ',
                'aliases' => json_encode(['ハンター×ハンター', 'Мисливець х Мисливець']),
                'studio_id' => $studios['Madhouse']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/hunter.jpg',
                'duration' => 24,
                'episodes_count' => 148,
                'first_air_date' => '2011-10-02',
                'last_air_date' => '2014-09-24',
                'imdb_score' => 8.9,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '11061']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/0/0c/Hunter_X_Hunter_2011_Logo.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=ZW6mRZufz10'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Hunter x Hunter — Аніме про пригоди мисливців',
                'meta_description' => 'Приєднуйтесь до Ґона у його подорожі, повній битв та відкриттів. Мисливець х Мисливець — класика жанру.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/hunter.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'autumn',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Токійський Гуль'),
                'name' => 'Токійський Гуль',
                'description' => 'Історія Канекі, студента, який після нападу гулів стає напівлюдиною-гулами, намагаючись жити між двома світами.',
                'aliases' => json_encode(['東京喰種トーキョーグール', 'Токійський гуль']),
                'studio_id' => $studios['Pierrot']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/ghoul.jpg',
                'duration' => 24,
                'episodes_count' => 12,
                'first_air_date' => '2014-07-04',
                'last_air_date' => '2014-09-19',
                'imdb_score' => 7.9,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '22319']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/5/5e/Tokyo_Ghoul_vol_1_cover.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=doT26h2EZvA'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Tokyo Ghoul — Темне фентезі про гулів',
                'meta_description' => 'Зануртеся у світ, де люди живуть поруч із кровожерливими гулями. Токійський гуль — аніме з драмою і жахом.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/ghoul.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'summer',
                'restricted_rating' => 'r',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Бездомний Бог'),
                'name' => 'Бездомний Бог',
                'description' => 'Історія про бога Ято, який намагається здобути славу, виконуючи різні завдання для людей.',
                'aliases' => json_encode(['ノラガミ', 'Бездомний Бог']),
                'studio_id' => $studios['Bones']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/noragami.webp',
                'duration' => 24,
                'episodes_count' => 25,
                'first_air_date' => '2014-01-05',
                'last_air_date' => '2015-12-31',
                'imdb_score' => 7.9,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '15765']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/e/e7/Noragami_anime.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=Sv5r-MZI6d8'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Noragami — Аніме про бога без даху над головою',
                'meta_description' => 'Пригоди Ято, бога-одинака, що намагається знайти своє місце в світі людей та богів.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/noragami.webp',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'winter',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Ванпанчмен'),
                'name' => 'Ванпанчмен',
                'description' => 'Історія героя Сайтами, який може перемогти будь-якого ворога одним ударом, шукаючи справжній виклик.',
                'aliases' => json_encode(['ワンパンマン', 'Ванпанчмен']),
                'studio_id' => $studios['Madhouse']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/one punch man.webp',
                'duration' => 24,
                'episodes_count' => 12,
                'first_air_date' => '2015-10-05',
                'last_air_date' => '2015-12-21',
                'imdb_score' => 8.8,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '30276']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/2/2c/One-Punch_Man_Volume_1_Cover.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=3HNyVQng0JA'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'One-Punch Man — Герой, що може все одним ударом',
                'meta_description' => 'Слідкуйте за пригодами Сайтами, який шукає достойних суперників. Ванпанчмен — комедійне бойове аніме.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/one punch man.webp',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'autumn',
                'restricted_rating' => 'pg_13',
                'source' => 'comic',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Стерто'),
                'name' => 'Стерто',
                'description' => 'Історія про чоловіка, який повертається в минуле, щоб запобігти трагедіям і врятувати своїх друзів.',
                'aliases' => json_encode(['僕だけがいない街', 'Місто, в якому мене немає']),
                'studio_id' => $studios['A-1 Pictures']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/erased.webp',
                'duration' => 24,
                'episodes_count' => 12,
                'first_air_date' => '2016-01-08',
                'last_air_date' => '2016-03-25',
                'imdb_score' => 8.5,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '31043']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/5/5e/Erased_Anime_Key_Visual.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=ICIsOmvKK38'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Erased — Таємниче аніме про подорож у часі',
                'meta_description' => 'Слідкуйте за подорожжю героя у минуле, щоб змінити долю. Місто, в якому мене немає — драматичний трилер.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/erased.webp',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'winter',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Дивні Пригоди ДжоДжо'),
                'name' => 'Дивні Пригоди ДжоДжо',
                'description' => 'Епічна сага про родину Джостарів та їх надприродні пригоди крізь покоління.',
                'aliases' => json_encode(['ジョジョの奇妙な冒険', 'Невероятные приключения ДжоДжо']),
                'studio_id' => $studios['David Production']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/jojo.webp',
                'duration' => 24,
                'episodes_count' => 152,
                'first_air_date' => '2012-10-06',
                'last_air_date' => null,
                'imdb_score' => 8.5,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '9253']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/d/d7/JoJo%27s_Bizarre_Adventure_2012_Logo.png'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=VKNXXlU-m1Y'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => "JoJo's Bizarre Adventure — Легендарна сага пригод",
                'meta_description' => 'Слідкуйте за дивовижними подорожами родини Джостар та їх бойовими здібностями. Унікальний стиль і історії.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/jojo.webp',
                'kind' => 'tv_series',
                'status' => 'ongoing',
                'period' => 'autumn',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Берсерк'),
                'name' => 'Берсерк',
                'description' => 'Темне фентезі про воїна Ґатса, який бореться зі своїм минулим, демонічними силами та долею.',
                'aliases' => json_encode(['剣風伝奇ベルセルク', 'Берсерк']),
                'studio_id' => $studios['OLM']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/berserk.avif',
                'duration' => 24,
                'episodes_count' => 25,
                'first_air_date' => '1997-10-08',
                'last_air_date' => '1998-04-01',
                'imdb_score' => 8.7,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '33']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/3/32/Berserk_%281997%29_DVD_Cover.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=ocQ6PDiP014'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Berserk — Культове темне фентезі аніме',
                'meta_description' => 'Зануртеся у похмурий світ Ґатса — мечника, що бореться зі зрадою та демонами. Берсерк — класика жанру seinen.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/berserk.avif',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'autumn',
                'restricted_rating' => 'r',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Сага про Вінланд'),
                'name' => 'Сага про Вінланд',
                'description' => 'Історія Торфіна — молодого вікінга, що прагне помсти за смерть батька та шукає справжній сенс життя в жорстокому світі війни.',
                'aliases' => json_encode(['ヴィンランド・サガ', 'Сага про Вінланд']),
                'studio_id' => $studios['Wit Studio']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/vinland.webp',
                'duration' => 24,
                'episodes_count' => 48,
                'first_air_date' => '2019-07-07',
                'last_air_date' => '2023-06-19',
                'imdb_score' => 8.8,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '37521']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/7/7b/Vinland_Saga_Key_Visual.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=f8JrZ7Q_p-8'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Vinland Saga — Історія вікінгів і помсти',
                'meta_description' => 'Подорож Торфіна крізь битви, втрати й внутрішній пошук. Vinland Saga — епічне історичне аніме.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/vinland.webp',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'summer',
                'restricted_rating' => 'r',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'slug' => Anime::generateSlug('Моя Геройська Академія'),
                'name' => 'Моя Геройська Академія',
                'description' => 'У світі, де понад 80% людей мають надздібності, юний Ідзуку Мідорія мріє стати героєм, попри відсутність сили.',
                'aliases' => json_encode(['僕のヒーローアカデミア', 'Моя геройська академія']),
                'studio_id' => $studios['Bones']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/my hero academia.jpg',
                'duration' => 24,
                'episodes_count' => 138,
                'first_air_date' => '2016-04-03',
                'last_air_date' => null,
                'imdb_score' => 8.3,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '31964']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/3/33/My_Hero_Academia_Season_1.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=wIb3nnOeves'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'My Hero Academia — Аніме про супергероїв та становлення',
                'meta_description' => 'Спостерігайте за становленням Мідорії, який доводить, що справжній герой — це не лише сила, а й серце.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/my hero academia.jpg',
                'kind' => 'tv_series',
                'status' => 'ongoing',
                'period' => 'spring',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],


            [
                'slug' => Anime::generateSlug('Крутий Вчитель Онідзука'),
                'name' => 'Крутий Вчитель Онідзука',
                'description' => 'Ейкіті Онідзука — колишній байкер і хуліган, який вирішує стати вчителем, щоб змінити життя учнів і себе самого.',
                'aliases' => json_encode(['グレート・ティーチャー・オニヅカ', 'Крутий вчитель Онідзука']),
                'studio_id' => $studios['Pierrot']->id,
                'countries' => json_encode(['Japan']),
                'poster' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/gto.jpg',
                'duration' => 24,
                'episodes_count' => 43,
                'first_air_date' => '1999-06-30',
                'last_air_date' => '2000-09-24',
                'imdb_score' => 8.5,
                //'api_sources' => json_encode([['source' => 'MyAnimeList', 'id' => '245']]),

                'attachments' => json_encode([
                    ['type' => 'image', 'url' => 'https://upload.wikimedia.org/wikipedia/en/1/10/GTOvolume1.jpg'],
                    ['type' => 'trailer', 'url' => 'https://www.youtube.com/watch?v=9iJxbpN9da0'],
                ]),

                'related' => json_encode([]),

                'is_published' => true,
                'meta_title' => 'Great Teacher Onizuka — Комедійна драма про незвичного вчителя',
                'meta_description' => 'Дивіться GTO — історія про байкера, який став учителем і змінив життя цілої школи.',
                'meta_image' => 'https://storageanimesite.blob.core.windows.net/images/animes/posters/gto.jpg',
                'kind' => 'tv_series',
                'status' => 'released',
                'period' => 'summer',
                'restricted_rating' => 'pg_13',
                'source' => 'manga',
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];



        foreach ($animes as $anime) {
            $exists = DB::table('animes')->where('slug', $anime['slug'])->exists();
            if (!$exists) {
                // Генеруємо новий ULID
                $ulid = (string) \Illuminate\Support\Str::ulid();

                // Додаємо ULID до даних аніме
                $animeData = array_merge(['id' => $ulid], $anime);

                DB::table('animes')->insert($animeData);
                $this->command->info("Аніме '{$anime['name']}' додано з ID: {$ulid}");
            } else {
                $this->command->warn("Аніме '{$anime['name']}' вже існує.");
            }
        }
    }
}
