<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\Commission;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionFactory extends Factory
{
    protected $model = Commission::class;

    public function definition(): array
    {
        return [
            'affiliate_id' => Affiliate::factory(),
            'game_session_id' => GameSession::factory(),
            'user_id' => User::factory(),
            'amount' => fake()->randomFloat(2, 1, 100),
            'type' => 'revshare',
            'status' => 'pending',
        ];
    }
}
