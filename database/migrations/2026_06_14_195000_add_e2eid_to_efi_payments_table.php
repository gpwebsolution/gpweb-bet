<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('efi_payments', function (Blueprint $table) {
            $table->string('e2eid', 100)->nullable()->after('txid')->index();
        });
    }

    public function down(): void
    {
        Schema::table('efi_payments', function (Blueprint $table) {
            $table->dropColumn('e2eid');
        });
    }
};
