<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@demo.com')->first();

        if ($user && !Wallet::where('user_id', $user->id)->exists()) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 1000.00,
                'balance_bonus' => 0,
                'refer_rewards' => 0,
                'anti_bot' => 0,
            ]);
        }
    }
}
