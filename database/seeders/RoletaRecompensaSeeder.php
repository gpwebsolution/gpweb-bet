<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoletaRecompensaSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            ['label' => 'R$ 0,10', 'value' => 0.10, 'weight' => 300, 'color' => '#e74c3c', 'sort_order' => 1, 'active' => 1],
            ['label' => 'R$ 0,25', 'value' => 0.25, 'weight' => 250, 'color' => '#3498db', 'sort_order' => 2, 'active' => 1],
            ['label' => 'R$ 0,50', 'value' => 0.50, 'weight' => 200, 'color' => '#2ecc71', 'sort_order' => 3, 'active' => 1],
            ['label' => 'R$ 1,00', 'value' => 1.00, 'weight' => 120, 'color' => '#f39c12', 'sort_order' => 4, 'active' => 1],
            ['label' => 'R$ 2,00', 'value' => 2.00, 'weight' => 60,  'color' => '#9b59b6', 'sort_order' => 5, 'active' => 1],
            ['label' => 'R$ 3,00', 'value' => 3.00, 'weight' => 30,  'color' => '#1abc9c', 'sort_order' => 6, 'active' => 1],
            ['label' => 'R$ 5,00', 'value' => 5.00, 'weight' => 10,  'color' => '#e67e22', 'sort_order' => 7, 'active' => 1],
        ];

        foreach ($rewards as $reward) {
            DB::table('roleta_recompensas')->insertOrIgnore($reward);
        }
    }
}
