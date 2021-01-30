<?php

namespace App\Providers;

use App\Services\Movies\Contracts\Service as MoviesServiceContract;
use App\Services\Movies\Service as MoviesService;
use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindMoviesService();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bind movies service in application.
     *
     * @return void
     */
    protected function bindMoviesService(): void
    {
        $this->app->alias(MoviesServiceContract::class, MoviesService::class);

        $this->app->bind(MoviesServiceContract::class, function ($app) {
            return new MoviesService(
                new Repository($app->config->get('services.movies'))
            );
        });
    }
}
