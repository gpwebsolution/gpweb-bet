<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function() {
    return redirect()->to('/admin/login');
});

Route::post('/login', [LoginController::class, 'login'])->name('login')->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/forgot-password',  [ResetPasswordController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/send-reset-link', [ResetPasswordController::class, 'sendResetLink'])->name('sendResetLink')->middleware('throttle:password-reset');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('showResetPasswordForm');
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword'])->name('resetPassword')->middleware('throttle:password-reset');
