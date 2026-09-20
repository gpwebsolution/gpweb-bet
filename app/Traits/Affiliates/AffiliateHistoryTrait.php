<?php

namespace App\Traits\Affiliates;

use App\Models\AffiliateHistory;
use App\Models\EfiPayment;
use App\Models\User;
use App\Notifications\NewDepositNotification;

trait AffiliateHistoryTrait
{
    public static function saveAffiliateHistory($user)
    {
        $sponsor = User::find($user->inviter);

        if (! empty($sponsor)) {
            $revshare = floatval($sponsor->affiliate_revenue_share);
            if ($revshare <= 0) {
                $revshare = floatval(\Helper::getSetting()->affiliate_default_percentage ?? 10);
            }

            if ($revshare > 0) {
                AffiliateHistory::create([
                    'user_id' => $user->id,
                    'inviter' => $sponsor->id,
                    'commission' => $revshare,
                    'commission_type' => 'revshare',
                    'deposited' => 0,
                    'losses' => 0,
                    'status' => 0,
                ]);
            }

            if (floatval($sponsor->affiliate_cpa) > 0) {
                AffiliateHistory::create([
                    'user_id' => $user->id,
                    'inviter' => $sponsor->id,
                    'commission' => $sponsor->affiliate_cpa,
                    'commission_type' => 'cpa',
                    'deposited' => 0,
                    'losses' => 0,
                    'status' => 0,
                ]);
            }

            return true;
        }

        return true;
    }

    public static function updateAffiliate($idTransaction, $userId, $price)
    {
        try {
            $payment = EfiPayment::with(['user'])
                ->where('payment_id', $idTransaction)
                ->where('status', 'pending')
                ->first();

            if (empty($payment)) {
                return;
            }

            AffiliateHistory::where('user_id', $userId)
                ->where('deposited', 0)
                ->where('status', 0)
                ->update(['deposited' => 1, 'deposited_amount' => $price]);

            $cpaHistory = AffiliateHistory::where('user_id', $userId)
                ->where('commission_type', 'cpa')
                ->where('deposited', 1)
                ->where('status', 0)
                ->lockForUpdate()
                ->first();

            if ($cpaHistory) {
                $sponsor = User::with('wallet')->find($cpaHistory->inviter);
                if ($sponsor && floatval($cpaHistory->deposited_amount) >= floatval($sponsor->affiliate_baseline)) {
                    if ($sponsor->wallet) {
                        $sponsor->wallet->increment('refer_rewards', floatval($sponsor->affiliate_cpa));
                        $cpaHistory->update([
                            'status' => 1,
                            'commission_paid' => $sponsor->affiliate_cpa,
                        ]);
                    }
                }
            }

            $admins = \Helper::getAdminUsers();
            foreach ($admins as $admin) {
                $admin->notify(new NewDepositNotification($payment->user->name, $price));
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('AffiliateHistoryTrait::updateAffiliate - '.$e->getMessage());

            return false;
        }
    }
}
