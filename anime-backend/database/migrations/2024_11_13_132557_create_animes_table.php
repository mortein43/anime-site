<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\Period;
use AnimeSite\Enums\RestrictedRating;
use AnimeSite\Enums\Source;
use AnimeSite\Enums\Status;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animes', function (Blueprint $table) {
            $table->ulid('id')->primary(); // Унікальний ідентифікатор
            $table->json('api_sources')->default(DB::raw("'[]'::json"))->nullable(); // JSON для API ідентифікаторів (source, id)
            $table->string('slug', 128)->unique(); // Унікальний slug
            $table->string('name', 248); // Назва фільму
            $table->text('description');
            //$table->string('image_name', 2048); // Шлях до зображення
            $table->json('aliases')->default(DB::raw("'[]'::json"))->nullable(); // JSON для масиву аліасів
            $table->foreignUlid('studio_id')->constrained()->cascadeOnDelete();
            $table->json('countries')->default(DB::raw("'[]'::json")); // JSON країн розробників enum Country
            $table->string('poster', 2048)->nullable(); // Шлях до постера
            $table->integer('duration')->nullable(); // Тривалість у хвилинах
            $table->integer('episodes_count')->nullable(); // Кількість епізодів
            $table->date('first_air_date')->nullable(); // Дата початку ефіру
            $table->date('last_air_date')->nullable(); // Дата завершення ефіру
            $table->decimal('imdb_score', 4, 2)->nullable(); // Оцінка на IMDB
            $table->json('attachments')->default(DB::raw("'[]'::json"));
            $table->json('related')->default(DB::raw("'[]'::json"))->nullable();
            $table->json('similars')->default(DB::raw("'[]'::json"))->nullable(); // JSON для схожих фільмів
            $table->boolean('is_published')->default(false); // Статус публікації
            $table->string('meta_title', 128)->nullable();
            $table->string('meta_description', 376)->nullable();
            $table->string('meta_image', 2048)->nullable();
            $table->timestamps();
        });

        Schema::table('animes', function (Blueprint $table) {
            $table->enumAlterColumn('kind', 'kind', Kind::class);
            $table->enumAlterColumn('status', 'status', Status::class);
            $table->enumAlterColumn('period', 'period', Period::class, nullable: true);
            $table->enumAlterColumn('restricted_rating', 'restricted_rating', RestrictedRating::class);
            $table->enumAlterColumn('source', 'source', Source::class);
        });

        DB::unprepared("
            ALTER TABLE animes
            ADD COLUMN searchable tsvector GENERATED ALWAYS AS (
                setweight(to_tsvector('ukrainian', name), 'A') ||
                setweight(to_tsvector('ukrainian', aliases), 'A') ||
                setweight(to_tsvector('ukrainian', description), 'B')
            ) STORED
        ");

        DB::unprepared('CREATE INDEX animes_searchable_index ON animes USING GIN (searchable)');
        DB::unprepared('CREATE INDEX animes_trgm_name_idx ON animes USING GIN (name gin_trgm_ops)');
    }

    public function down(): void
    {
        Schema::dropIfExists('animes');

        DB::unprepared('DROP TYPE source');
        DB::unprepared('DROP TYPE restricted_rating');
        DB::unprepared('DROP TYPE period');
        DB::unprepared('DROP TYPE status');
        DB::unprepared('DROP TYPE kind');

        DB::unprepared('DROP INDEX IF EXISTS animes_searchable_index');
        DB::unprepared('DROP INDEX IF EXISTS animes_trgm_name_idx');
    }
};
