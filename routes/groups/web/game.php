<?php

use App\Http\Controllers\Api\Providers\VGamesController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [VGamesController::class, 'index'])->name('game.list');
Route::get('/game/{slug}', [VGamesController::class, 'show'])->name('game.index');
