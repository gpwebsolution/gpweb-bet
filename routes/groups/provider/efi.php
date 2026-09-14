<?php

use App\Http\Controllers\Gateway\EfiController;
use App\Http\Controllers\Gateway\WebhookController;
use Illuminate\Support\Facades\Route;

Route::any('efi/callback', [EfiController::class, 'callbackMethod']);

Route::post('webhook/pix', [WebhookController::class, 'handlePix']);

Route::middleware(['auth', 'throttle:30,1'])->group(function () {
    Route::get('efi/status', [EfiController::class, 'status']);

    Route::post('efi/qrcode-pix', [EfiController::class, 'getQRCodePix'])
        ->middleware('throttle:10,1');

    Route::post('efi/consult-status-transaction', [EfiController::class, 'consultStatusTransactionPix']);

    Route::post('efi/confirm-payment', [EfiController::class, 'confirmPayment']);

    Route::get('efi/payment-stream/{paymentId}', [EfiController::class, 'paymentStream']);

    Route::get('efi/saque/{id}', [EfiController::class, 'saqueFromModal'])->name('efi.saque');
    Route::get('efi/cancelsaque/{id}', [EfiController::class, 'cancelSaqueFromModal'])->name('efi.cancelsaque');
});
