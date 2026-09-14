<?php

use App\Models\Affiliate;
use App\Models\Commission;
use App\Models\GameSession;
use App\Models\User;
use App\Services\AffiliateService;

it('processa comissão revshare após aposta lucrativa', function () {
    $affiliate = Affiliate::factory()->create([
        'model' => 'revshare',
        'revshare_percentage' => 10,
    ]);

    $user = User::factory()->create([
        'referred_by' => $affiliate->code,
        'balance' => 1000,
    ]);

    $session = GameSession::factory()->create([
        'user_id' => $user->id,
        'profit' => 100,
    ]);

    app(AffiliateService::class)->processRevShare($session);

    expect(Commission::where('affiliate_id', $affiliate->id)->sum('amount'))->toBe(10.0);
});

it('processa comissão CPA no cadastro com código de afiliado', function () {
    $affiliate = Affiliate::factory()->create([
        'model' => 'cpa',
        'cpa_value' => 50,
    ]);

    $user = User::factory()->create();

    app(AffiliateService::class)->registerReferral($user, $affiliate->code);

    expect(Commission::where('affiliate_id', $affiliate->id)->count())->toBe(1);
    expect(Commission::first()->amount)->toBe(50.0);
});
