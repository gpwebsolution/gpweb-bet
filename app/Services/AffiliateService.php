<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\User;

class AffiliateService
{
    public function processReferral(User $user, string $code): void
    {
        $affiliate = Affiliate::where('code', $code)->first();
        if ($affiliate) {
            $user->update(['referred_by' => $code]);
        }
    }

    public function registerReferral(User $user, string $code): void
    {
        $this->processReferral($user, $code);
    }
}
