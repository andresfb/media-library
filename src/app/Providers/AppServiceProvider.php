<?php

namespace App\Providers;

use App\Services\GeneratePostsService;
use App\Services\ImportMediaService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ImportMediaService::class, function () {
            return new ImportMediaService();
        });

        $this->app->bind(GeneratePostsService::class, function () {
            return new GeneratePostsService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
