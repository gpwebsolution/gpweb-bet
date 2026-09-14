<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories') && !Schema::hasTable('providers')) {
            Schema::table('games', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
            });

            Schema::table('game_exclusives', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
            });

            Schema::rename('categories', 'providers');

            Schema::table('games', function (Blueprint $table) {
                $table->renameColumn('category_id', 'provider_id');
            });

            Schema::table('game_exclusives', function (Blueprint $table) {
                $table->renameColumn('category_id', 'provider_id');
            });

            Schema::table('games', function (Blueprint $table) {
                $table->foreign('provider_id')->references('id')->on('providers')->cascadeOnDelete();
            });

            Schema::table('game_exclusives', function (Blueprint $table) {
                $table->foreign('provider_id')->references('id')->on('providers')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('providers') && !Schema::hasTable('categories')) {
            Schema::table('games', function (Blueprint $table) {
                $table->dropForeign(['provider_id']);
            });

            Schema::table('game_exclusives', function (Blueprint $table) {
                $table->dropForeign(['provider_id']);
            });

            Schema::rename('providers', 'categories');

            Schema::table('games', function (Blueprint $table) {
                $table->renameColumn('provider_id', 'category_id');
            });

            Schema::table('game_exclusives', function (Blueprint $table) {
                $table->renameColumn('provider_id', 'category_id');
            });

            Schema::table('games', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            });

            Schema::table('game_exclusives', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            });
        }
    }
};
