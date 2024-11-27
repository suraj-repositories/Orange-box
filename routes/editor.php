<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', function($userid){
    dd('editor submited', $userid);
})->name('dashboard');
