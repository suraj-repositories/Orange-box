<?php

use App\Http\Controllers\Common\FileController;
use App\Http\Controllers\Test\TestingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
     return view('welcome');

    // $fileName = "abc.txt";
    // $fileName = pathinfo($fileName)['filename'] . "(".  5 .  ")" . (isset(pathinfo($fileName)['extension']) ? '.' . pathinfo($fileName)['extension'] : '');
    // dd($fileName);
})->name('home');

Route::get('/log-me-out', function () {
    Auth::logout();
    return redirect()->route('login')->with('success', 'Logout done!');
});

Route::get('/testing', [TestingController::class, 'testing']);

Route::controller(FileController::class)->middleware('auth')->group(function(){
    Route::delete('file/{file}', 'destroy')->name('file.delete');
    Route::patch('file/{file}', 'rename')->name('file.rename');
});
