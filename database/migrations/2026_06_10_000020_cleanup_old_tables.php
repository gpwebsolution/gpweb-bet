<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('suit_pay_payments');

        if (Schema::hasTable('wallets') && Schema::hasTable('carteiras')) {
            Schema::drop('wallets');
        }
    }

    public function down(): void
    {
    }
};
