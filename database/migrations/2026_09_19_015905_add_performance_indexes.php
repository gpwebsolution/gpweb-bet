<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('inviter');
            $table->index('status');
        });

        Schema::table('game_sessions', function (Blueprint $table) {
            $table->index('game_id');
            $table->index('type');
            $table->index('created_at');
        });

        Schema::table('game_exclusives', function (Blueprint $table) {
            $table->index('uuid');
            $table->index('active');
            $table->index('views');
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });

        Schema::table('affiliates', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('affiliate_histories', function (Blueprint $table) {
            $table->index('status');
            $table->index('commission_type');
        });

        Schema::table('vip_bonuses', function (Blueprint $table) {
            $table->index('type');
            $table->index('claimed_at');
        });

        Schema::table('vips', function (Blueprint $table) {
            $table->index('active');
        });

        Schema::table('efi_payments', function (Blueprint $table) {
            $table->index('created_at');
        });

        if (Schema::hasTable('withdrawals')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['inviter']);
            $table->dropIndex(['status']);
        });

        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropIndex(['game_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('game_exclusives', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropIndex(['active']);
            $table->dropIndex(['views']);
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('affiliate_histories', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['commission_type']);
        });

        Schema::table('vip_bonuses', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['claimed_at']);
        });

        Schema::table('vips', function (Blueprint $table) {
            $table->dropIndex(['active']);
        });

        Schema::table('efi_payments', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        if (Schema::hasTable('withdrawals')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                $table->dropIndex(['status']);
            });
        }
    }
};
