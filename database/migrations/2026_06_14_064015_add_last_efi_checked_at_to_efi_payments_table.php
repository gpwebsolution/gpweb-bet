<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('efi_payments', function (Blueprint $table) {
            $table->timestamp('last_efi_checked_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('efi_payments', function (Blueprint $table) {
            $table->dropColumn('last_efi_checked_at');
        });
    }
};
