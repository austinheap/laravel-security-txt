<?php
namespace EllisTheDev\Robots;

use Illuminate\Support\ServiceProvider;

/**
 * Class RobotsServiceProvider
 *
 * @package EllisTheDev\Robots
 */
class RobotsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('robots', function ()
        {
            return new Robots;
        });
    }
}
