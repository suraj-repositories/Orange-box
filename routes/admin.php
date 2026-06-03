<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Docs\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('documentation/templates')->name('docs.templates.')->group(function () {
    Route::get('/', [TemplateController::class, 'index'])->name('index');
    Route::get('/create', [TemplateController::class, 'create'])->name('create');
    Route::post('/', [TemplateController::class, 'store'])->name('store');
    Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('edit');
    Route::get('/{template}', [TemplateController::class, 'show'])->name('show');
    Route::put('/{template}', [TemplateController::class, 'update'])->name('update');
    Route::patch('/{template}/status', [TemplateController::class, 'updateStatus'])->name('status.update');

});
