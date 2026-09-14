<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BannerSeeder::class,
            ProviderSeeder::class,
            RoletaRecompensaSeeder::class,
            SettingSeeder::class,
            RoleSeeder::class,
            WalletSeeder::class,
            GameSeeder::class,
            GameExclusiveSeeder::class,
            VipSeeder::class,
        ]);
    }
}
