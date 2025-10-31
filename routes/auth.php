<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LockScreenController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function(){
 Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

 Route::get('/lock-screen', [LockScreenController::class, 'index'])->name('lock-screen.index');
 Route::post('/lock-screen', [LockScreenController::class, 'unlock'])->name('unlock');

});
