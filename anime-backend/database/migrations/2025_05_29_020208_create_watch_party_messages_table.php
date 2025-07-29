<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watch_party_messages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('watch_party_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watch_party_messages');
    }
};
