# robots.txt Generator for Laravel 5

[![Current Release](https://img.shields.io/github/release/ellisthedev/laravel-5-robots.svg)](https://github.com/ellisthedev/laravel-5-robots/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/ellisthedev/laravel-5-robots.svg)](https://packagist.org/packages/ellisthedev/laravel-5-robots)
[![Build Status](https://travis-ci.org/ellisthedev/laravel-5-robots.svg?branch=master)](https://travis-ci.org/ellisthedev/laravel-5-robots)
[![Code Climate](https://codeclimate.com/github/ellisthedev/laravel-5-robots/badges/gpa.svg)](https://codeclimate.com/github/ellisthedev/laravel-5-robots)
[![Test Coverage](https://codeclimate.com/github/ellisthedev/laravel-5-robots/badges/coverage.svg)](https://codeclimate.com/github/ellisthedev/laravel-5-robots)

This is a fork of https://github.com/jayhealey/Robots. It appears development
has stalled on the original repository.

The purpose of this fork is to introduce Laravel 5 compatibility and PSR-4 and
PSR-2 (for Laravel 5.1).

# Installation

## Step 1: Composer

Add the package to your `composer.json`:

```
{
    "require": {
        "ellisthedev/laravel-5-robots": "~0.1.0"
    }
}
```

## Step 2: Configuration

Add the following to your `config/app.php` in the `providers` array:

```
'EllisTheDev\Robots\RobotsServiceProvider',
```

You can also optionally add the following to the `aliases` array:

```
'Robots' => 'EllisTheDev\Robots\RobotsFacade',
```

# Usage

Add the following to your routes file:

```php
Route::get('robots.txt', function ()
{
    if (App::environment() == 'production') {
        // If on the live server, serve a nice, welcoming robots.txt.
        Robots::addUserAgent('*');
        Robots::addSitemap('sitemap.xml');
    } else {
        // If you're on any other server, tell everyone to go away.
        Robots::addDisallow('*');
    }

    return Response::make(Robots::generate(), 200, ['Content-Type' => 'text/plain']);
});
```

Refer to the [Robots.php](src/Robots.php) for API usage.

# License

[MIT](LICENSE)
