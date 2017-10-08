<?php
/**
 * src/SecurityTxtServiceProvider.php
 *
 * @package     laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.2.5
 */

declare(strict_types=1);

namespace AustinHeap\Security\Txt;

/**
 * SecurityTxtServiceProvider
 */
class SecurityTxtServiceProvider extends \Illuminate\Support\ServiceProvider
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
            __DIR__ . '/config/security-txt.php' => config_path('security-txt.php'),
        ]);

        if (! $this->app->routesAreCached())
            require __DIR__ . '/routes/security-txt.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/security-txt.php', 'security-txt'
        );

        $this->app->singleton('securitytxt', function () {
            return new SecurityTxt;
        });
    }

}
