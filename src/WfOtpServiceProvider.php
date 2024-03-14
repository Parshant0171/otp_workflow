<?php

namespace Jgu\Wfotp;

use Illuminate\Support\ServiceProvider;
use Jgu\Wfotp\Facades\Wfo;

class WfOtpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Wfotp');

        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        $this->publishes([
            __DIR__ . '/../config/wfo.php' => config_path('wfo.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/wfo.php', 'wfo');

        // Register the service the package provides.
        $this->app->singleton('wfo', function ($app) {
            return new Wfo;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['wfo'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/wfo.php' => config_path('wfo.php'),
        ], 'wfo.config');        

    }
}
