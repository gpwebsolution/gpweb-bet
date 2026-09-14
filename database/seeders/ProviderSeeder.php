<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            ['name' => 'Originals', 'slug' => 'originals', 'description' => 'Jogos originais da casa', 'image' => null],
        ];

        foreach ($providers as $provider) {
            $existing = DB::table('providers')->where('slug', $provider['slug'])->first();
            if (!$existing) {
                DB::table('providers')->insert($provider);
            }
        }
    }
}
