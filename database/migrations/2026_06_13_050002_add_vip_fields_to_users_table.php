<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'vip_id')) {
                $table->foreignId('vip_id')->nullable()->constrained('vips')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'vip_leveled_at')) {
                $table->timestamp('vip_leveled_at')->nullable()->after('vip_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['vip_leveled_at', 'vip_id']);
        });
    }
};
