<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roleta_recompensas')) return;

        Schema::create('roleta_recompensas', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->decimal('value', 10, 2);
            $table->integer('weight')->default(1)->comment('Maior peso = mais chance de cair');
            $table->string('color', 20)->default('#e74c3c');
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(1);
            $table->boolean('garantido')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roleta_recompensas');
    }
};
