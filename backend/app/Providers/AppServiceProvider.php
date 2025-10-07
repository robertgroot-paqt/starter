<?php

namespace App\Providers;

use App\Data\Responses\ResponseDataCollectableResolver;
use App\Data\Responses\ResponseDataResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelData\Resolvers\TransformedDataCollectableResolver;
use Spatie\LaravelData\Resolvers\TransformedDataResolver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TransformedDataResolver::class, ResponseDataResolver::class);
        $this->app->bind(TransformedDataCollectableResolver::class, ResponseDataCollectableResolver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading();
    }
}
