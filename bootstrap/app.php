<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
            then: function(){
                Route::namespace('Auth')->middleware(['web'])->group(base_path('routes/auth.php'));
                Route::namespace('Admin')->middleware(['web', 'admin'])->prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));
                Route::namespace('Editor')->middleware(['web', 'editor'])->prefix('e/{userid}')->name('editor.')->group(base_path('routes/editor.php'));
                Route::namespace('User')->middleware(['web', 'user'])->prefix('u/{userid}')->name('user.')->group(base_path('routes/user.php'));
            },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'admin' => App\Http\Middleware\IsAdmin::class,
            'editor' => App\Http\Middleware\IsEditor::class,
            'user' => App\Http\Middleware\IsUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
