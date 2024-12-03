<?php

namespace App\Providers;

use App\Services\FileService;
use App\Services\Impl\FileServiceImpl;
use Illuminate\Support\Facades\Artisan;
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
        //
        if (app()->environment('local')) {
            Artisan::call('route:clear');
        }
    }
}
