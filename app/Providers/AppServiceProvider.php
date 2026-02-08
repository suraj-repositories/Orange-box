<?php

namespace App\Providers;

use App\Facades\Setting;
use App\Models\Comment;
use App\Models\File;
use App\Models\FolderFactory;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\SubTask;
use App\Models\Task;
use App\Observers\CommentObserver;
use App\Policies\FilePolicy;
use App\Policies\FolderFactoryPolicy;
use App\Policies\ProjectBoardPolicy;
use App\Policies\ProjectModulePolicy;
use App\Policies\SubTaskPolicy;
use App\Policies\TaskPolicy;
use App\Services\EditorJsService;
use App\Services\FileService;
use App\Services\Impl\EditorJsServiceImpl;
use App\Services\Impl\FileServiceImpl;
use App\Services\Impl\MarkdownServiceImpl;
use App\Services\MarkdownService;
use App\Services\Impl\SettingsServiceImpl;
use App\Services\Impl\SuggestionServiceImpl;
use App\Services\SuggestionService;
use App\Services\GitService;
use  App\Services\Impl\GitServiceImpl;
use Illuminate\Pagination\Paginator;
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
        $this->app->singleton('settings', function () {
            return new SettingsServiceImpl();
        });
        $this->app->singleton(FileService::class, FileServiceImpl::class);
        $this->app->singleton(EditorJsService::class, EditorJsServiceImpl::class);
        $this->app->singleton(MarkdownService::class, MarkdownServiceImpl::class);
        $this->app->singleton(SuggestionService::class, SuggestionServiceImpl::class);
        $this->app->singleton(GitService::class, GitServiceImpl::class);

        class_alias(Setting::class, 'Setting');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::policy(File::class, FilePolicy::class);
        Gate::policy(FolderFactory::class, FolderFactoryPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(ProjectBoard::class, ProjectBoardPolicy::class);
        Gate::policy(ProjectModule::class, ProjectModulePolicy::class);
        Gate::policy(SubTask::class, SubTaskPolicy::class);

        Comment::observe(CommentObserver::class);

        Paginator::useBootstrapFive();

        view()->composer('*', function ($view) {
            $view->with('settings', app('settings'));
        });
        //
        if (app()->environment('local')) {
            $this->app->booted(function () {
                Artisan::call('route:clear');
            });
        }
    }
}
