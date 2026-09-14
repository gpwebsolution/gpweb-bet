<?php

use App\Http\Controllers\Panel\VipController;
use Illuminate\Support\Facades\Route;

Route::prefix('vip')
    ->as('vip.')
    ->group(function () {
        Route::get('/', [VipController::class, 'index'])->name('index');
        Route::post('/claim-weekly', [VipController::class, 'claimWeekly'])->name('claim.weekly');
        Route::post('/claim-monthly', [VipController::class, 'claimMonthly'])->name('claim.monthly');
        Route::post('/claim-level-reward', [VipController::class, 'claimLevelReward'])->name('claim.level_reward');
    });
