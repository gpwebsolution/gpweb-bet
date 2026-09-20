<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            ['provider_id' => 1, 'name' => 'Fortune Tiger', 'uuid' => 'fortunetiger', 'image' => 'games/fortunetiger.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Fortune OX', 'uuid' => 'fortuneox', 'image' => 'games/fortuneox.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Fortune Mouse', 'uuid' => 'fortunemouse', 'image' => 'games/fortunemouse.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Fortune Rabbit', 'uuid' => 'fortunerabbit', 'image' => 'games/fortunerabbit.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Fortune Panda', 'uuid' => 'fortunepanda', 'image' => 'games/fortunepanda.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Jack Frost', 'uuid' => 'jackfrost', 'image' => 'games/jackfrost.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Phoenix Rises', 'uuid' => 'phoenixrises', 'image' => 'games/phoenixrises.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Queen of Bounty', 'uuid' => 'queenofbounty', 'image' => 'games/queenofbounty.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Hood vs Woolf', 'uuid' => 'hoodvswoolf', 'image' => 'games/hoodvswoolf.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Song Kran Party', 'uuid' => 'songkranparty', 'image' => 'games/songkranparty.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Bikini Paradise', 'uuid' => 'bikiniparadise', 'image' => 'games/bikiniparadise.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
            ['provider_id' => 1, 'name' => 'Treasures of Aztec', 'uuid' => 'treasuresofaztec', 'image' => 'games/treasuresofaztec.svg', 'type' => 'slot', 'provider' => 'originals', 'provider_service' => 'originals', 'active' => 1],
        ];

        foreach ($games as $game) {
            DB::table('games')->insertOrIgnore($game);
        }
    }
}
