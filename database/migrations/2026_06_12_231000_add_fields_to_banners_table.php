<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('banners', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('link');
            }
            if (!Schema::hasColumn('banners', 'active')) {
                $table->boolean('active')->default(1)->after('sort_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('banners', 'title')) $drop[] = 'title';
            if (Schema::hasColumn('banners', 'sort_order')) $drop[] = 'sort_order';
            if (Schema::hasColumn('banners', 'active')) $drop[] = 'active';
            if ($drop) $table->dropColumn($drop);
        });
    }
};
