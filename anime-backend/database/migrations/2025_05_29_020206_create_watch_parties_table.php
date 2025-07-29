<?php

use AnimeSite\Enums\WatchPartyStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('watch_parties', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('episode_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_private')->default(false);
            $table->string('password')->nullable();
            $table->integer('max_viewers')->default(10);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
        Schema::table('watch_parties', function (Blueprint $table) {
            $table->enumAlterColumn('watch_party_status', 'watch_party_status', WatchPartyStatus::class, WatchPartyStatus::WAITING->value);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watch_party_messages');
        Schema::dropIfExists('watch_parties');
        DB::unprepared('DROP TYPE watch_party_status');

    }
};
