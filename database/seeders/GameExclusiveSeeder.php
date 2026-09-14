<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameExclusiveSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            ['provider_id' => 1, 'uuid' => 'fortunetiger', 'name' => 'Fortune Tiger', 'description' => 'Jogo Fortune Tiger - O tigrinho da sorte!', 'cover' => 'games/fortunetiger.svg', 'icon' => 'games/fortunetiger.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'fortuneox', 'name' => 'Fortune OX', 'description' => 'Jogo Fortune OX - O touro da fortuna!', 'cover' => 'games/fortuneox.svg', 'icon' => 'games/fortuneox.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'fortunemouse', 'name' => 'Fortune Mouse', 'description' => 'Jogo Fortune Mouse', 'cover' => 'games/fortunemouse.svg', 'icon' => 'games/fortunemouse.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'fortunerabbit', 'name' => 'Fortune Rabbit', 'description' => 'Jogo Fortune Rabbit', 'cover' => 'games/fortunerabbit.svg', 'icon' => 'games/fortunerabbit.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'fortunepanda', 'name' => 'Fortune Panda', 'description' => 'Jogo Fortune Panda', 'cover' => 'games/fortunepanda.svg', 'icon' => 'games/fortunepanda.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'jackfrost', 'name' => 'Jack Frost', 'description' => 'Jogo Jack Frost', 'cover' => 'games/jackfrost.svg', 'icon' => 'games/jackfrost.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'phoenixrises', 'name' => 'Phoenix Rises', 'description' => 'Jogo Phoenix Rises', 'cover' => 'games/phoenixrises.svg', 'icon' => 'games/phoenixrises.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'queenofbounty', 'name' => 'Queen of Bounty', 'description' => 'Jogo Queen of Bounty', 'cover' => 'games/queenofbounty.svg', 'icon' => 'games/queenofbounty.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'hoodvswoolf', 'name' => 'Hood vs Woolf', 'description' => 'Jogo Hood vs Woolf', 'cover' => 'games/hoodvswoolf.svg', 'icon' => 'games/hoodvswoolf.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'songkranparty', 'name' => 'Song Kran Party', 'description' => 'Jogo Song Kran Party', 'cover' => 'games/songkranparty.svg', 'icon' => 'games/songkranparty.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'bikiniparadise', 'name' => 'Bikini Paradise', 'description' => 'Jogo Bikini Paradise', 'cover' => 'games/bikiniparadise.svg', 'icon' => 'games/bikiniparadise.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
            ['provider_id' => 1, 'uuid' => 'treasuresofaztec', 'name' => 'Treasures of Aztec', 'description' => 'Jogo Treasures of Aztec', 'cover' => 'games/treasuresofaztec.svg', 'icon' => 'games/treasuresofaztec.svg', 'active' => 1, 'winLength' => 3, 'loseLength' => 5, 'influencer_winLength' => 20, 'influencer_loseLength' => 1, 'loseResults' => '', 'demoWinResults' => '', 'winResults' => '', 'iconsJson' => ''],
        ];

        foreach ($games as $game) {
            DB::table('game_exclusives')->updateOrInsert(
                ['uuid' => $game['uuid']],
                $game
            );
        }
    }
}
