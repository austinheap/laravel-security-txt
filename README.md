# Laravel 5.5+ `security.txt` Package

![laravel-security-txt banner from the documentation](docs/img/banner-1544x500.png?raw=true)

[![License](https://img.shields.io/packagist/l/austinheap/laravel-security-txt.svg)](https://github.com/austinheap/laravel-security-txt/blob/master/LICENSE.md)
[![Current Release](https://img.shields.io/github/release/austinheap/laravel-security-txt.svg)](https://github.com/austinheap/laravel-security-txt/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/austinheap/laravel-security-txt.svg)](https://packagist.org/packages/austinheap/laravel-security-txt)
[![Build Status](https://travis-ci.org/austinheap/laravel-security-txt.svg?branch=master)](https://travis-ci.org/austinheap/laravel-security-txt)
[![Dependency Status](https://gemnasium.com/badges/github.com/austinheap/laravel-security-txt.svg)](https://gemnasium.com/github.com/austinheap/laravel-security-txt)
[![Scrutinizer CI](https://scrutinizer-ci.com/g/austinheap/laravel-security-txt/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/austinheap/laravel-security-txt/)
[![StyleCI](https://styleci.io/repos/106077909/shield?branch=master)](https://styleci.io/repos/106077909)
[![Maintainability](https://api.codeclimate.com/v1/badges/ca1e10510f778f520bb5/maintainability)](https://codeclimate.com/github/austinheap/laravel-security-txt/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ca1e10510f778f520bb5/test_coverage)](https://codeclimate.com/github/austinheap/laravel-security-txt/test_coverage)
[![SensioLabs](https://insight.sensiolabs.com/projects/9fe66b91-58ad-4bc3-9ec9-37b396bb4837/mini.png)](https://insight.sensiolabs.com/projects/9fe66b91-58ad-4bc3-9ec9-37b396bb4837)

## A package for serving `security.txt` in Laravel 5.5+, based on configuration settings.

The purpose of this project is to create a set-it-and-forget-it package that can be
installed without much effort to get a Laravel project compliant with the current
[`security.txt`](https://securitytxt.org/) spec. It is therefore highly opinionated
but built for configuration.

When enabled, it allows access to all clients and serves up the `security.txt`.
Otherwise, it operates almost identically to Laravel's default configuration,
denying access to all clients.

[`security.txt`](https://github.com/securitytxt) is a [draft](https://tools.ietf.org/html/draft-foudil-securitytxt-00)
"standard" which allows websites to define security policies. This "standard"
sets clear guidelines for security researchers on how to report security issues,
and allows bug bounty programs to define a scope. Security.txt is the equivalent
of `robots.txt`, but for security issues.

There is [documentation for `laravel-security-txt` online](https://austinheap.github.io/laravel-security-txt/),
the source of which is in the [`docs/`](https://github.com/austinheap/laravel-security-txt/tree/master/docs)
directory. The most logical place to start are the [docs for the `SecurityTxt` class](https://austinheap.github.io/laravel-security-txt/classes/AustinHeap.Security.Txt.SecurityTxt.html).

## Table of Contents

* [Summary](#a-package-for-serving-securitytxt-in-laravel-55-based-on-configuration-settings)
* [Installation](#installation)
    + [Step 1: Composer](#step-1-composer)
    + [Step 2: Remove any existing `security.txt`](#step-2-remove-any-existing-securitytxt)
    + [Step 3: Enable the package (Optional)](#step-3-enable-the-package-optional)
    + [Step 4: Configure the package](#step-4-configure-the-package)
* [Full `.env` Example](#full-env-example)
* [Unit Tests](#unit-tests)
* [References](#references)
* [Credits](#credits)
* [License](#license)

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
        "austinheap/laravel-security-txt": "0.3.*"
    }
}
```

### Step 2: Remove any existing `security.txt`

Laravel doesn't ship with a default `security.txt` file. If you have added one, it needs to be removed for the configured route to work.

```bash
$ rm public/.well-known/security.txt
```

### Step 3: Enable the package (Optional)

This package implements Laravel 5.5's auto-discovery feature. After you install it the package provider and facade are added automatically.

If you would like to declare the provider and/or alias explicitly, then add the service provider to your `config/app.php`:

Add the service provider to your `config/app.php`:

```php
'providers' => [
    //
    AustinHeap\Security\Txt\SecurityTxtServiceProvider::class,
];
```

And then add the alias to your `config/app.php`:

```php
'aliases' => [
    //
    'SecurityTxt' => AustinHeap\Security\Txt\SecurityTxtFacade::class,
];
```

### Step 4: Configure the package

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

## Full `.env` Example

After installing the package with composer, simply add the following to your .env file:

```bash
SECURITY_TXT_ENABLED=true
SECURITY_TXT_CACHE=true
SECURITY_TXT_CONTACT=security@your-site.com
SECURITY_TXT_ENCRYPTION=https://your-site.com/pgp.key
SECURITY_TXT_DISCLOSURE=full
SECURITY_TXT_ACKNOWLEDGEMENT=https://your-site.com/security-champions
```

Now point your browser to `http://your-site.com/.well-known/security.txt` and you should see:

```
# Our security address
Contact: me@austinheap.com

# Our PGP key
Encryption: http://some.url/pgp.key

# Our disclosure policy
Disclosure: Full

# Our public acknowledgement
Acknowledgement: http://some.url/acks

#
# Generated by "laravel-security-txt" v0.4.0 (https://github.com/austinheap/laravel-security-txt/releases/tag/v0.4.0)
# using "php-security-txt" v0.4.0 (https://github.com/austinheap/php-security-txt/releases/tag/v0.4.0)
# in 0.041008 seconds on 2017-11-22 20:31:25.
#
# Cache is enabled with key "cache:AustinHeap\Security\Txt\SecurityTxt".
#
```

## Unit Tests

This package has aggressive unit tests built with the wonderful [orchestral/testbench](https://github.com/orchestral/testbench)
package which is built on top of PHPUnit.

There are [code coverage reports for `laravel-security-txt`](https://austinheap.github.io/laravel-security-txt/coverage/)
available online.

## References

- [A Method for Web Security Policies (draft-foudil-securitytxt-00)](https://tools.ietf.org/html/draft-foudil-securitytxt-00)
- [php-security-txt](https://github.com/austinheap/php-security-txt)
- [securitytext.org](https://securitytext.org/)

## Credits

This is a fork of [InfusionWeb/laravel-robots-route](https://github.com/InfusionWeb/laravel-robots-route),
which was a fork of [ellisthedev/laravel-5-robots](https://github.com/ellisthedev/laravel-5-robots),
which was a fork of [jayhealey/Robots](https://github.com/jayhealey/Robots),
which was based on earlier work.

- [ellisio/laravel-5-robots Contributors](https://github.com/ellisio/laravel-5-robots/graphs/contributors)
- [InfusionWeb/laravel-robots-route Contributors](https://github.com/InfusionWeb/laravel-robots-route/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
