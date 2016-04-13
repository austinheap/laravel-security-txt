# Laravel 5 robots.txt Route

## A route for serving a basic robots.txt in Laravel 5.1+, based on configuration settings.

This is a fork of [ellisthedev/laravel-5-robots](https://github.com/ellisthedev/laravel-5-robots),
which was a fork of [jayhealey/Robots](https://github.com/jayhealey/Robots),
which was based on earlier work.

The purpose of this fork is to create a set-it-and-forget-it package that can be
installed without much effort. It is therefore highly opinionated and not built
for configuration.

When enabled, it allows access to all clients and serves up the sitemap.xml.
Otherwise, it operates almost identically to Laravel's default configuration,
denying access to all clients.

## Installation

### Step 1: Composer

Via Composer command line:

```bash
$ composer require infusionweb/laravel-robots-route
```

Or add the package to your `composer.json`:

```json
{
    "require": {
        "infusionweb/laravel-robots-route": "~0.1.0"
    }
}
```

### Step 2: Remove the existing robots.txt

Laravel ships with a default robots.txt which disallows all clients. It needs to be removed for the configured route to work.

```bash
$ rm public/robots.txt
```

### Step 3: Enable the route

Publish the package config file:

```bash
$ php artisan vendor:publish --provider="InfusionWeb\Laravel\Robots\RobotsServiceProvider"
```

You may now allow clients via robots.txt by editing the `config/robots.php` file, opening up the site to search engines:

```php
return [
    'allow' => env('ROBOTS_ALLOW', true),
];
```

Or simply setting the the `ROBOTS_ALLOW` environment variable to true, via the Laravel `.env` file or hosting environment.

```bash
ROBOTS_ALLOW=true
```

## Credits

- [Russell Keppner](https://github.com/rkeppner)
- [All Contributors](https://github.com/InfusionWeb/laravel-robots-route/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
