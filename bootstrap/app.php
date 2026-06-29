<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {

            $groupWithFallback = function ($callback) {
                $callback();
                Route::fallback(fn() => abort(404));
            };

            Route::prefix('api')
                ->name('api.')
                ->group(base_path('routes/api.php'));

            Route::middleware(['web'])
                ->group(base_path('routes/auth.php'));

            Route::prefix('admin')
                ->name('admin.')
                ->middleware(['web', 'auth', 'admin', 'lockscreen'])
                ->group(fn() => $groupWithFallback(fn() => require base_path('routes/admin.php')));

            Route::prefix('e/{userid?}')
                ->name('editor.')
                ->middleware(['web', 'auth', 'editor', 'lockscreen'])
                ->group(fn() => $groupWithFallback(fn() => require base_path('routes/editor.php')));

            Route::prefix('u/{userid?}')
                ->name('user.')
                ->middleware(['web', 'auth', 'user', 'lockscreen'])
                ->group(fn() => $groupWithFallback(fn() => require base_path('routes/user.php')));

            Route::middleware(['web', 'docs'])
                ->group(fn() => $groupWithFallback(fn() => require base_path('routes/docs.php')));
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
            'docs' => App\Http\Middleware\DocsMiddleware::class,
        ]);
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Throwable $e, Request $request) {

            if (
                $e instanceof ValidationException ||
                $e instanceof AuthenticationException ||
                $e instanceof AuthorizationException
            ) {
                return null;
            }

            $middleware = $request->route()?->gatherMiddleware() ?? [];

            Log::error($e->getMessage());

            $section = match (true) {
                in_array('admin', $middleware, true) => 'admin',
                in_array('editor', $middleware, true) => 'editor',
                in_array('user', $middleware, true)   => 'user',
                in_array('docs', $middleware, true)   => 'docs',
                default                               => null,
            };

            if ($section === null) {

                $docsPrefixes = [
                    'docs',
                    'docs-extras',
                    'docs-search',
                    'docs-v',
                    'docs-xt',
                    'docs-v-ajax',
                ];

                $section = match (true) {
                    $request->segment(1) === 'admin' => 'admin',
                    $request->segment(1) === 'e'     => 'editor',
                    $request->segment(1) === 'u'     => 'user',

                    in_array($request->segment(2), $docsPrefixes, true) => 'docs',

                    default => 'web',
                };
            }


            $status = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : 500;

            $view = "errors.{$section}.{$status}";

            if (! view()->exists($view)) {
                $view = "errors.{$section}.default";
            }

            if (! view()->exists($view)) {
                $view = "errors.{$status}";
            }

            if (! view()->exists($view)) {
                return null;
            }

            return response()->view($view, [
                'exception' => $e,
            ], $status);
        });
    })->create();
