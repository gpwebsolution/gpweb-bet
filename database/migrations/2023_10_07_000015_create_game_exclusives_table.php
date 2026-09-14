<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('game_exclusives')) return;

        Schema::create('game_exclusives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
            $table->string('uuid');
            $table->string('name');
            $table->text('description');
            $table->string('cover');
            $table->string('icon');
            $table->bigInteger('winLength')->default(3);
            $table->bigInteger('loseLength')->default(20);
            $table->bigInteger('influencer_winLength')->default(20);
            $table->bigInteger('influencer_loseLength')->default(1);
            $table->tinyInteger('active')->default(1);
            $table->bigInteger('views')->default(0);
            $table->text('loseResults');
            $table->text('demoWinResults');
            $table->text('winResults');
            $table->text('iconsJson');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_exclusives');
    }
};
