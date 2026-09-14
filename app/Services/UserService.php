<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function createUser(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'last_name' => '',
            'status' => 'active',
            'affiliate_revenue_share' => 0,
            'affiliate_cpa' => 0,
            'affiliate_baseline' => 0,
            'is_demo_agent' => 0,
            'banned' => 0,
            'affiliate_code' => $this->generateAffiliateCode(),
        ];

        if (isset($data['inviter'])) {
            $userData['inviter'] = (int) $data['inviter'];
        }

        $user = User::create($userData);

        $user->assignRole('user');

        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'balance_bonus' => 0,
            'refer_rewards' => 0,
            'anti_bot' => 0,
        ]);

        return $user;
    }

    private function generateAffiliateCode(): string
    {
        $prefix = 'MB';
        do {
            $code = $prefix . strtoupper(Str::random(8));
        } while (User::where('affiliate_code', $code)->exists());

        return $code;
    }
}
