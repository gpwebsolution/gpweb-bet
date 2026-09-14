<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('withdrawals') && !Schema::hasTable('saques')) {
            Schema::rename('withdrawals', 'saques');
        }

        if (Schema::hasColumn('settings', 'min_withdrawal') && !Schema::hasColumn('settings', 'min_saque')) {
            Schema::table('settings', function ($table) {
                $table->renameColumn('min_withdrawal', 'min_saque');
                $table->renameColumn('max_withdrawal', 'max_saque');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('saques') && !Schema::hasTable('withdrawals')) {
            Schema::rename('saques', 'withdrawals');
        }

        if (Schema::hasColumn('settings', 'min_saque') && !Schema::hasColumn('settings', 'min_withdrawal')) {
            Schema::table('settings', function ($table) {
                $table->renameColumn('min_saque', 'min_withdrawal');
                $table->renameColumn('max_saque', 'max_withdrawal');
            });
        }
    }
};
