<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\File;
use App\Observers\CommentObserver;
use App\Policies\FilePolicy;
use App\Services\FileService;
use App\Services\Impl\FileServiceImpl;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(FileService::class, FileServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::policy(File::class, FilePolicy::class);

        Comment::observe(CommentObserver::class);

        //
        if (app()->environment('local')) {
            $this->app->booted(function () {
                Artisan::call('route:clear');
            });
        }

    }
}
