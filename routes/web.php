<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/log-me-out', function () {
    Auth::logout();
    return redirect()->route('login')->with('success', 'Logout done!');
});
