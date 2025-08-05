<?php

namespace WsPackages\CombinationGenerate\Providers;

use WsPackages\CombinationGenerate\Services\CombinationService;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Combination Generate package
 */
class CombinationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Package bootstrapping logic can be added here
    }

    /**
     * Register the package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(CombinationService::class, function ($app) {
            return new CombinationService();
        });

        // Register as singleton for better performance
        $this->app->singleton('combination-service', function ($app) {
            return new CombinationService();
        });

        // Register facade
        $this->app->singleton('combination', function ($app) {
            return new CombinationService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            CombinationService::class,
            'combination-service',
            'combination',
        ];
    }
}
