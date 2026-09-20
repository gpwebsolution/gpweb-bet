<?php

use App\Http\Controllers\Panel\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('carteira')
    ->as('wallet.')
    ->group(function ()
    {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/saques', [WalletController::class, 'viewSaques'])->name('saques');
        Route::get('/deposits', [WalletController::class, 'viewDeposits'])->name('deposits');
        Route::get('/hide-balance', [WalletController::class, 'hideBalance'])->name('hidebalance');

        Route::get('/depositar', [WalletController::class, 'viewDepositForm'])->name('deposit_form');
        Route::get('/sacar', [WalletController::class, 'viewSaqueForm'])->name('saque_form');

        Route::post('/deposit', [WalletController::class, 'generateDeposit'])->name('deposit')->middleware('throttle:financial');
        Route::post('/saque', [WalletController::class, 'requestSaque'])->name('saque')->middleware('throttle:financial');
    });
