<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/log-me-out', function () {
    Auth::logout();
    return redirect()->back();
});
