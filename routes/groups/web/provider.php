<?php

use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/provedor/{slug}', [HomeController::class, 'showGameByProvider'])->name('provider.index');
