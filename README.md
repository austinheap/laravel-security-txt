# Laravel 5.5+ `security.txt` Package

[![Current Release](https://img.shields.io/github/release/austinheap/laravel-security-txt.svg)](https://github.com/austinheap/laravel-security-txt/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/austinheap/laravel-security-txt.svg)](https://packagist.org/packages/austinheap/laravel-security-txt)
[![Build Status](https://travis-ci.org/austinheap/laravel-security-txt.svg?branch=master)](https://travis-ci.org/austinheap/laravel-security-txt)
[![Code Climate](https://codeclimate.com/github/austinheap/laravel-security-txt/badges/gpa.svg)](https://codeclimate.com/github/austinheap/laravel-security-txt)
[![Test Coverage](https://codeclimate.com/github/austinheap/laravel-security-txt/badges/coverage.svg)](https://codeclimate.com/github/austinheap/laravel-security-txt)

## A package for serving `security.txt` in Laravel 5.5+, based on configuration settings.

The purpose of this project is to create a set-it-and-forget-it package that can be
installed without much effort. It is therefore highly opinionated but built
for configuration.

When enabled, it allows access to all clients and serves up the `security.txt`.
Otherwise, it operates almost identically to Laravel's default configuration,
denying access to all clients.

There is [documentation for `laravel-security-txt` online](https://austinheap.github.io/laravel-security-txt/),
the source of which is in the [`docs/`](https://github.com/austinheap/laravel-security-txt/tree/master/docs)
directory. The most logical place to start are the [docs for the `SecurityTxt` class](https://austinheap.github.io/laravel-security-txt/classes/AustinHeap.Security.Txt.SecurityTxt.html).

## Installation

### Step 1: Composer

Via Composer command line:

```bash
$ composer require austinheap/laravel-security-txt
```

Or add the package to your `composer.json`:

```json
{
    "require": {
        "austinheap/laravel-security-txt": "^0.2.5"
    }
}
```

### Step 2: Remove any existing `security.txt`

Laravel doesn't ship with a default `security.txt` file. If you have added one, it needs to be removed for the configured route to work.

```bash
$ rm public/.well-known/security.txt
```

### Step 3: Enable the route

Add the service provider to your `config/app.php`:

```php
'providers' => [
    //
    AustinHeap\Security\Txt\SecurityTxtServiceProvider::class,
];
```

Publish the package config file:

```bash
$ php artisan vendor:publish --provider="AustinHeap\Security\Txt\SecurityTxtServiceProvider"
```

You may now allow clients via `security.txt` by editing the `config/security-txt.php` file, opening up the route to the public:

```php
return [
    'enabled' => env('SECURITY_TXT_ENABLED', true),
];
```

Or simply setting the the `SECURITY_TXT_ENABLED` environment variable to true, via the Laravel `.env` file or hosting environment.

```bash
SECURITY_TXT_ENABLED=true
```

## Full .env Example

After installing the package with composer, simply add the following to your .env file:

```bash
SECURITY_TXT_ENABLED=true
SECURITY_TXT_CONTACT=security@your-site.com
SECURITY_TXT_ENCRYPTION=https://your-site.com/pgp.key
SECURITY_TXT_DISCLOSURE=full
SECURITY_TXT_ACKNOWLEDGEMENT=https://your-site.com/security-champions
```

## References

- [A Method for Web Security Policies (draft-foudil-securitytxt-00)](https://tools.ietf.org/html/draft-foudil-securitytxt-00)

## Credits

This is a fork of [InfusionWeb/laravel-robots-route](https://github.com/InfusionWeb/laravel-robots-route),
which was a fork of [ellisthedev/laravel-5-robots](https://github.com/ellisthedev/laravel-5-robots),
which was a fork of [jayhealey/Robots](https://github.com/jayhealey/Robots),
which was based on earlier work.

- [ellisio/laravel-5-robots Contributors](https://github.com/ellisio/laravel-5-robots/graphs/contributors)
- [InfusionWeb/laravel-robots-route Contributors](https://github.com/InfusionWeb/laravel-robots-route/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
