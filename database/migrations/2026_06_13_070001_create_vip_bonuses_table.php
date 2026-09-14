<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vip_bonuses')) return;

        Schema::create('vip_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vip_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // weekly, monthly
            $table->decimal('amount', 10, 2);
            $table->timestamp('claimed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vip_bonuses');
    }
};
