<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('saques')) return;

        Schema::create('saques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 20, 2)->default(0);
            $table->string('type');
            $table->string('proof')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('chave_pix');
            $table->string('tipo_chave');
            $table->string('document')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saques');
    }
};
