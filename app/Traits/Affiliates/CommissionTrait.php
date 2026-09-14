<?php

namespace App\Traits\Affiliates;

use App\Models\Affiliate;
use App\Models\AffiliateHistory;
use App\Models\Commission;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;

trait CommissionTrait
{
    public static function processAffiliateCommission(User $user, float $betAmount): void
    {
        if ($betAmount <= 0) return;

        $inviterId = $user->inviter;
        if (empty($inviterId)) return;

        $inviter = User::find($inviterId);
        if (!$inviter) return;

        $revsharePercent = floatval($inviter->affiliate_revenue_share);
        if ($revsharePercent <= 0) {
            $setting = Setting::first();
            $revsharePercent = floatval($setting->affiliate_default_percentage ?? 10);
        }
        if ($revsharePercent <= 0) return;

        $commission = ($revsharePercent / 100) * $betAmount;
        if ($commission <= 0) return;

        $wallet = Wallet::where('user_id', $inviter->id)->first();
        if ($wallet) {
            $wallet->increment('refer_rewards', $commission);
        }

        AffiliateHistory::create([
            'user_id' => $user->id,
            'inviter' => $inviter->id,
            'commission' => $revsharePercent,
            'commission_type' => 'revshare',
            'losses' => 1,
            'losses_amount' => $betAmount,
            'deposited' => 0,
            'commission_paid' => $commission,
            'status' => 0,
        ]);

        $affiliate = Affiliate::where('user_id', $inviter->id)->first();
        if ($affiliate) {
            Commission::create([
                'affiliate_id' => $affiliate->id,
                'user_id' => $user->id,
                'amount' => $commission,
                'type' => 'revshare',
                'status' => 'pending',
            ]);
            $affiliate->increment('total_commission', $commission);
            $affiliate->increment('pending_commission', $commission);
        }
    }
}
