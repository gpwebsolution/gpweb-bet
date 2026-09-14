<?php

namespace Database\Seeders;

use App\Models\Vip;
use Illuminate\Database\Seeder;

class VipSeeder extends Seeder
{
    public function run(): void
    {
        $vips = [
            ['level' => 1,  'name' => 'Bronze 1',     'weekly_bonus' => 0.50,  'monthly_bonus' => 1.00,  'level_up_bonus' => 1.00,  'min_deposit' => 50,   'min_bets' => 100,   'color' => '#cd7f32'],
            ['level' => 2,  'name' => 'Bronze 2',     'weekly_bonus' => 1.00,  'monthly_bonus' => 3.00,  'level_up_bonus' => 3.00,  'min_deposit' => 100,  'min_bets' => 300,   'color' => '#cd7f32'],
            ['level' => 3,  'name' => 'Prata 1',      'weekly_bonus' => 2.00,  'monthly_bonus' => 5.00,  'level_up_bonus' => 5.00,  'min_deposit' => 200,  'min_bets' => 1000,  'color' => '#c0c0c0'],
            ['level' => 4,  'name' => 'Prata 2',      'weekly_bonus' => 3.50,  'monthly_bonus' => 10.00, 'level_up_bonus' => 10.00, 'min_deposit' => 350,  'min_bets' => 2500,  'color' => '#c0c0c0'],
            ['level' => 5,  'name' => 'Ouro 1',       'weekly_bonus' => 5.00,  'monthly_bonus' => 15.00, 'level_up_bonus' => 15.00, 'min_deposit' => 500,  'min_bets' => 5000,  'color' => '#ffd700'],
            ['level' => 6,  'name' => 'Ouro 2',       'weekly_bonus' => 8.00,  'monthly_bonus' => 25.00, 'level_up_bonus' => 25.00, 'min_deposit' => 750,  'min_bets' => 10000, 'color' => '#ffd700'],
            ['level' => 7,  'name' => 'Platina 1',    'weekly_bonus' => 12.00, 'monthly_bonus' => 40.00, 'level_up_bonus' => 40.00, 'min_deposit' => 1000, 'min_bets' => 20000, 'color' => '#e5e4e2'],
            ['level' => 8,  'name' => 'Platina 2',    'weekly_bonus' => 18.00, 'monthly_bonus' => 60.00, 'level_up_bonus' => 60.00, 'min_deposit' => 1500, 'min_bets' => 40000, 'color' => '#e5e4e2'],
            ['level' => 9,  'name' => 'Diamante 1',   'weekly_bonus' => 25.00, 'monthly_bonus' => 90.00, 'level_up_bonus' => 90.00, 'min_deposit' => 2000, 'min_bets' => 75000, 'color' => '#b9f2ff'],
            ['level' => 10, 'name' => 'Diamante 2',   'weekly_bonus' => 35.00, 'monthly_bonus' => 120.00,'level_up_bonus' => 120.00,'min_deposit' => 3000, 'min_bets' => 150000,'color' => '#b9f2ff'],
        ];

        foreach ($vips as $vip) {
            Vip::updateOrCreate(['level' => $vip['level']], $vip);
        }
    }
}
