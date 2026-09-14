<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vips')) return;

        Schema::create('vips', function (Blueprint $table) {
            $table->id();
            $table->integer('level')->unique();
            $table->string('name');
            $table->decimal('weekly_bonus', 10, 2)->default(0);
            $table->decimal('monthly_bonus', 10, 2)->default(0);
            $table->decimal('level_up_bonus', 10, 2)->default(0);
            $table->decimal('min_deposit', 10, 2)->default(0);
            $table->integer('min_bets')->default(0);
            $table->string('color', 20)->default('#e63946');
            $table->string('icon', 50)->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vips');
    }
};
