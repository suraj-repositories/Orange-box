<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api')->name('api.')->group(base_path('routes/api.php'));
            Route::middleware(['web'])->group(base_path('routes/auth.php'));
            Route::middleware(['web', 'admin', 'lockscreen'])->prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));
            Route::middleware(['web', 'editor', 'lockscreen'])->prefix('e/{userid?}')->name('editor.')->group(base_path('routes/editor.php'));
            Route::middleware(['web', 'user', 'lockscreen'])->prefix('u/{userid?}')->name('user.')->group(base_path('routes/user.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'admin' => App\Http\Middleware\AdminMiddleware::class,
            'editor' => App\Http\Middleware\EditorMiddleware::class,
            'user' => App\Http\Middleware\UserMiddleware::class,
            'collab' => App\Http\Middleware\CollaborationRouteMiddleware::class,
            'lockscreen' => App\Http\Middleware\LockScreenMiddleware::class,
        ]);
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
