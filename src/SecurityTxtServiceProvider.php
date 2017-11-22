<?php
/**
 * src/SecurityTxtServiceProvider.php.
 *
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.3.2
 */
declare(strict_types=1);

namespace AustinHeap\Security\Txt;

/**
 * SecurityTxtServiceProvider.
 *
 * @link        https://github.com/austinheap/laravel-security-txt
 * @link        https://packagist.org/packages/austinheap/laravel-security-txt
 * @link        https://austinheap.github.io/laravel-security-txt/classes/AustinHeap.Security.Txt.SecurityTxtServiceProvider.html
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

        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/routes/security-txt.php';
        }

        if (! defined('LARAVEL_SECURITY_TXT_VERSION')) {
            define('LARAVEL_SECURITY_TXT_VERSION', SecurityTxtHelper::VERSION);
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
            __DIR__.'/config/security-txt.php', 'security-txt'
        );

        $this->app->singleton('SecurityTxt', function () {
            return new SecurityTxtHelper;
        });
    }

    /**
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public static function getInstance()
    {
        return app('SecurityTxt');
    }
}
