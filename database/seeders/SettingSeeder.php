<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        if (Setting::count() === 0) {
            Setting::create([
                'software_name' => 'MarioBET',
                'software_description' => 'Cassino Online | Jogos de Slot e Apostas em Futebol',
                'prefix' => 'R$',
                'currency_code' => 'BRL',
                'decimal_format' => 'dot',
                'currency_position' => 'left',
                'min_deposit' => 20,
                'max_deposit' => 5000,
                'min_saque' => 20,
                'max_saque' => 5000,
                'initial_bonus' => 0,
                'ngr_percent' => 0,
                'revshare_reverse' => false,
                'storage' => 'local',
            ]);
        }
    }
}
