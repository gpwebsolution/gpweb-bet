<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'social_instagram_icon')) {
                $table->string('social_instagram_icon')->nullable()->after('instagram');
            }
            if (!Schema::hasColumn('settings', 'social_tiktok_icon')) {
                $table->string('social_tiktok_icon')->nullable()->after('tiktok');
            }
            if (!Schema::hasColumn('settings', 'social_whatsapp_icon')) {
                $table->string('social_whatsapp_icon')->nullable()->after('whatsapp');
            }
            if (!Schema::hasColumn('settings', 'social_discord_icon')) {
                $table->string('social_discord_icon')->nullable()->after('discord');
            }
            if (!Schema::hasColumn('settings', 'social_telegram_icon')) {
                $table->string('social_telegram_icon')->nullable()->after('telegram');
            }
            if (!Schema::hasColumn('settings', 'social_twitter_icon')) {
                $table->string('social_twitter_icon')->nullable()->after('twitter');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'social_instagram_icon',
                'social_tiktok_icon',
                'social_whatsapp_icon',
                'social_discord_icon',
                'social_telegram_icon',
                'social_twitter_icon',
            ]);
        });
    }
};
