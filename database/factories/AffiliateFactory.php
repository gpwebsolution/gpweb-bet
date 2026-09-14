<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'code' => strtoupper(fake()->bothify('??####')),
            'model' => 'revshare',
            'revshare_percentage' => 10.00,
            'cpa_value' => 0,
            'total_referred' => 0,
            'total_commission' => 0,
            'pending_commission' => 0,
            'status' => true,
        ];
    }
}
