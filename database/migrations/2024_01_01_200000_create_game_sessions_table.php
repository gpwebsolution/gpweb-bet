<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('game_sessions')) return;

        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('game_id')->default(0);
            $table->decimal('bet_amount', 20, 2)->default(0);
            $table->decimal('result_amount', 20, 2)->default(0);
            $table->decimal('profit', 20, 2)->default(0);
            $table->string('type', 20)->default('bet');
            $table->string('round_id', 40)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
