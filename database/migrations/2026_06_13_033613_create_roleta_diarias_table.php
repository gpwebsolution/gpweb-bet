<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roleta_diarias')) return;

        Schema::create('roleta_diarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('roleta_recompensa_id')->constrained('roleta_recompensas')->cascadeOnDelete();
            $table->decimal('value', 10, 2);
            $table->date('spin_date')->nullable();
            $table->unique(['user_id', 'spin_date'], 'user_spin_date_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roleta_diarias');
    }
};
