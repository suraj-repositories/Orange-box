<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LockScreenController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/google-login', [SocialMediaLoginController::class, 'googleLogin'])->name('google.login');
    Route::get('/auth/google/callback', [SocialMediaLoginController::class, 'googleHandler'])->name('googleHandler');

    Route::get('/github-login', [SocialMediaLoginController::class, 'githubLogin'])->name('github.login');
    Route::get('/auth/github/callback', [SocialMediaLoginController::class, 'githubHandler'])->name('github.callback');

    Route::get('/auth/facebook', [SocialMediaLoginController::class, 'facebookPage'])->name('facebook.login');
    Route::get('/auth/facebook/callback', [SocialMediaLoginController::class, 'facebookRedirect'])->name('facebook.callback');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

});

Route::middleware('auth')->group(function(){
 Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

 Route::get('/lock-screen', [LockScreenController::class, 'index'])->middleware('lockscreen')->name('lock-screen.index');
 Route::post('/lock-screen', [LockScreenController::class, 'lock'])->name('lock');
 Route::put('/lock-screen', [LockScreenController::class, 'unlock'])->name('unlock');

});
