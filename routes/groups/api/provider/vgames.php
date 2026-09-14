<?php

use App\Http\Controllers\Api\Providers\VGamesController;
use Illuminate\Support\Facades\Route;

Route::prefix('vgames')
    ->group(function ()
    {
        Route::match(['GET', 'POST'], '/{token}/{action}', [VGamesController::class, 'vgameProvider']);
    });
