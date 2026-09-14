<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('affiliates')) return;

        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('model')->default('revshare');
            $table->decimal('revshare_percentage', 5, 2)->default(30.00);
            $table->decimal('cpa_value', 10, 2)->default(0.00);
            $table->integer('total_referred')->default(0);
            $table->decimal('total_commission', 10, 2)->default(0.00);
            $table->decimal('pending_commission', 10, 2)->default(0.00);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
