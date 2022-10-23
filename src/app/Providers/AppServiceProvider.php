<?php

namespace App\Providers;

use App\Services\AvatarGeneratorService;
use App\Services\ContentGenerators\ContentOrchestratorService;
use App\Services\ConvertHeicToJpgService;
use App\Services\CreateThumbnailService;
use App\Services\ExtractExifService;
use App\Services\GenerateFeedService;
use App\Services\GeneratePostsService;
use App\Services\ImportMediaService;
use Illuminate\Pagination\Paginator;
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

        $this->app->bind(ConvertHeicToJpgService::class, function () {
            return new ConvertHeicToJpgService();
        });

        $this->app->bind(AvatarGeneratorService::class, function () {
            return new AvatarGeneratorService();
        });

        $this->app->bind(ExtractExifService::class, function () {
            return new ExtractExifService();
        });

        $this->app->bind(CreateThumbnailService::class, function () {
            return new CreateThumbnailService();
        });

        $this->app->bind(GeneratePostsService::class, function () {
            return new GeneratePostsService(new ContentOrchestratorService());
        });

        $this->app->bind(GenerateFeedService::class, function () {
            return new GenerateFeedService(new AvatarGeneratorService());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
