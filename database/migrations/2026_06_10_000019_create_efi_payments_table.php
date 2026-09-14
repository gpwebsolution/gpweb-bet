<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('efi_payments')) return;

        Schema::create('efi_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique()->index();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 20, 2)->default(0);
            $table->string('cpf', 20)->nullable();
            $table->text('pix_qrcode')->nullable();
            $table->text('pix_copy_paste')->nullable();
            $table->string('txid', 100)->nullable()->index();
            $table->string('status', 20)->default('pending')->index();
            $table->string('token', 100)->nullable()->index();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('efi_payments');
    }
};
