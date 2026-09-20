<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->string('game_name')->nullable()->after('game_id');
            $table->string('game_uuid')->nullable()->after('game_name');
            $table->string('provider')->default('originals')->after('game_uuid');
            $table->string('balance_source')->default('balance')->after('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropColumn(['game_name', 'game_uuid', 'provider', 'balance_source']);
        });
    }
};
