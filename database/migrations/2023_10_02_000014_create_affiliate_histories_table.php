<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('affiliate_histories')) return;

        Schema::create('affiliate_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('inviter')->index();
            $table->decimal('commission', 20, 2)->default(0);
            $table->string('commission_type')->nullable();
            $table->tinyInteger('deposited')->default(0);
            $table->decimal('deposited_amount', 20, 2)->default(0);
            $table->tinyInteger('losses')->default(0);
            $table->decimal('losses_amount', 20, 2)->default(0);
            $table->decimal('commission_paid', 20, 2)->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_histories');
    }
};
