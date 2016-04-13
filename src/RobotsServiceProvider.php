<?php

namespace InfusionWeb\Laravel\Robots;

use Illuminate\Support\ServiceProvider;

class RobotsServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/robots.php' => config_path('robots.php'),
        ]);

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routing/routes.php';
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/robots.php', 'length'
        );

        $this->app->singleton('robots', function () {
            return new Robots;
        });
    }

}
