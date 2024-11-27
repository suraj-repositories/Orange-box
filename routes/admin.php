<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', function(){
    dd('admin submited');
})->name('dashboard');
