<?php

use App\Http\Controllers\Common\FileController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\DailyDigestController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::controller(DashboardController::class)->group(function(){
    Route::get('dashboard', 'index')->name('dashboard');
});

Route::controller(DailyDigestController::class)->group(function(){
    Route::get('daily-digest', 'index')->name('daily-digest');
    Route::get('daily-digest/create', 'create')->name('daily-digest.create');
    Route::post('daily-digest', 'store')->name('daily-digest.store');
    Route::get('daily-digest/{dailyDigest}', 'show')->name('daily-digest.show');
    Route::get('daily-digest/{dailyDigest}/edit', 'edit')->name('daily-digest.edit');
    Route::post('daily-digest/{dailyDigest}', 'update')->name('daily-digest.update');
    Route::delete('daily-digest/{dailyDigest}', 'destroy')->name('daily-digest.delete');

    Route::post('daily-digest/{dailyDigest}/like', 'like')->name('daily-digest.like');
    Route::post('daily-digest/{dailyDigest}/dislike', 'dislike')->name('daily-digest.dislike');
});

Route::controller(CommentController::class)->group(function(){
    Route::post('comments', 'store')->name('comments.store');
    Route::post('comments/load-comments', 'loadComment')->name('comments.load.comments');
});
