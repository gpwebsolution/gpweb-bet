<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) return;

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('software_name')->nullable();
            $table->string('software_description')->nullable();
            $table->string('software_logo_white')->nullable();
            $table->string('software_logo_black')->nullable();
            $table->string('software_favicon')->nullable();
            $table->string('currency_code')->default('BRL');
            $table->string('decimal_format', 20)->default('dot');
            $table->string('currency_position', 20)->default('left');
            $table->string('prefix')->default('R$');
            $table->string('storage')->default('local');
            $table->decimal('min_deposit', 20, 2)->default(20);
            $table->decimal('max_deposit', 20, 2)->default(0);
            $table->decimal('min_saque', 20, 2)->default(20);
            $table->decimal('max_saque', 20, 2)->default(0);
            $table->bigInteger('ngr_percent')->default(20);
            $table->bigInteger('revshare_percentage')->default(20);
            $table->tinyInteger('revshare_reverse')->default(0);
            $table->decimal('affiliate_default_percentage', 5, 2)->default(10.00);
            $table->decimal('affiliate_default_cpa', 10, 2)->default(0.00);
            $table->decimal('affiliate_default_baseline', 10, 2)->default(50.00);
            $table->bigInteger('soccer_percentage')->default(30);
            $table->bigInteger('initial_bonus')->default(100);
            $table->string('software_smtp_type', 30)->nullable();
            $table->string('software_smtp_mail_host', 100)->nullable();
            $table->string('software_smtp_mail_port', 30)->nullable();
            $table->string('software_smtp_mail_username', 191)->nullable();
            $table->string('software_smtp_mail_password', 100)->nullable();
            $table->string('software_smtp_mail_encryption', 30)->nullable();
            $table->string('software_smtp_mail_from_address', 191)->nullable();
            $table->string('software_smtp_mail_from_name', 191)->nullable();
            $table->string('instagram')->nullable();
            $table->string('discord')->nullable();
            $table->string('telegram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('whatsapp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
