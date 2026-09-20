<?php

namespace App\Services;

use App\Models\Vip;
use Illuminate\Support\Facades\DB;

class VipService
{
    public static function resolveForUser($user): array
    {
        $currentVip = $user->vip;
        $nextVip = null;
        $userDeposit = 0;
        $userBets = 0;
        $progressDeposit = 0;
        $progressBets = 0;

        $userDeposit = (float) DB::table('efi_payments')
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');

        $userBets = (float) ($user->wallet->total_bet ?? 0);

        if ($currentVip) {
            $nextVip = Vip::where('active', true)
                ->where('level', '>', $currentVip->level)
                ->orderBy('level')
                ->first();
        } else {
            $nextVip = Vip::where('active', true)->orderBy('level')->first();
        }

        if ($nextVip) {
            $progressDeposit = $nextVip->min_deposit > 0
                ? min(100, round(($userDeposit / $nextVip->min_deposit) * 100))
                : ($nextVip->min_deposit == 0 ? 100 : 0);

            $progressBets = $nextVip->min_bets > 0
                ? min(100, round(($userBets / $nextVip->min_bets) * 100))
                : ($nextVip->min_bets == 0 ? 100 : 0);
        }

        return [
            'currentVip' => $currentVip,
            'nextVip' => $nextVip,
            'progressDeposit' => $progressDeposit,
            'progressBets' => $progressBets,
            'userDeposit' => $userDeposit,
            'userBets' => $userBets,
        ];
    }
}
