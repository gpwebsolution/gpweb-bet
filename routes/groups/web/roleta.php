<?php

use App\Http\Controllers\Web\RoletaController;
use Illuminate\Support\Facades\Route;

Route::get('/roleta', [RoletaController::class, 'index'])->name('roleta.index');
Route::post('/roleta/girar', [RoletaController::class, 'spin'])->name('roleta.spin')->middleware('throttle:roleta');
