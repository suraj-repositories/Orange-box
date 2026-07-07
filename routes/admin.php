<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Docs\TemplateController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\FaqController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UsersController::class, 'index'])->name('index');
    Route::patch('/{user}/status', [UsersController::class, 'updateStatus'])->name('status.update');
    Route::delete('/{user}', [UsersController::class, 'destroy'])->name('delete');

    Route::get('/{user}/impersonate', [UsersController::class, 'loginUser'])->name('login');
});
Route::prefix('faqs')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('faq.index');
    Route::patch('/{faq}/status', [FaqController::class, 'updateStatus'])->name('faq.status.update');
    Route::delete('/{faq}', [FaqController::class, 'destroy'])->name('faq.delete');
    Route::post('/', [FaqController::class, 'store'])->name('faq.store');
    Route::get('/create', [FaqController::class, 'create'])->name('faq.create');
    Route::get('/{faq}/edit', [FaqController::class, 'edit'])->name('faq.edit');
    Route::put('/{faq}', [FaqController::class, 'update'])->name('faq.update');
});
Route::prefix('contact-messages')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('contact.index');
    Route::patch('/{contact}/status', [ContactController::class, 'updateStatus'])->name('contact.status.update');
    Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('contact.delete');
});



Route::prefix('documentation/templates')->name('docs.templates.')->group(function () {
    Route::get('/', [TemplateController::class, 'index'])->name('index');
    Route::get('/create', [TemplateController::class, 'create'])->name('create');
    Route::post('/', [TemplateController::class, 'store'])->name('store');
    Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('edit');
    Route::get('/{template}', [TemplateController::class, 'show'])->name('show');
    Route::put('/{template}', [TemplateController::class, 'update'])->name('update');
    Route::patch('/{template}/status', [TemplateController::class, 'updateStatus'])->name('status.update');
});
