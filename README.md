# Laravel Mobile Verifier

[![Build Status](https://travis-ci.org/mohammad-fouladgar/laravel-mobile-verifier.svg?branch=master)](https://travis-ci.org/mohammad-fouladgar/laravel-mobile-verifier)
[![Coverage Status](https://coveralls.io/repos/github/mohammad-fouladgar/laravel-mobile-verifier/badge.svg)](https://coveralls.io/github/mohammad-fouladgar/laravel-mobile-verifier)
[![Quality Score](https://img.shields.io/scrutinizer/g/mohammad-fouladgar/laravel-mobile-verifier.svg?style=flat-square)](https://scrutinizer-ci.com/g/mohammad-fouladgar/laravel-mobile-verifier)
[![Latest Stable Version](https://poser.pugx.org/fouladgar/laravel-mobile-verifier/v/stable)](https://packagist.org/packages/fouladgar/laravel-mobile-verifier)
[![Total Downloads](https://poser.pugx.org/fouladgar/laravel-mobile-verifier/downloads)](https://packagist.org/packages/fouladgar/laravel-mobile-verifier)
[![License](https://poser.pugx.org/fouladgar/laravel-mobile-verifier/license)](https://packagist.org/packages/fouladgar/laravel-mobile-verifier)


## Introduction
Many web applications require users to verify their mobile numbers before using the application. Rather than forcing you to re-implement this on each application, this package provides convenient methods for sending and verifying mobile verification requests.


## Installation

You can install the package via composer:

```shell
composer require fouladgar/laravel-mobile-verifier
```
> Laravel 5.5 uses Package Auto-Discovery, so you are not required to add ServiceProvider manually.

### Laravel <= 5.4.x

If you don't use Auto-Discovery, add the ServiceProvider to the providers array in ``config/app.php`` file

```php
'providers' => [
  /*
   * Package Service Providers...
   */
  Fouladgar\MobileVerifier\ServiceProvider::class,
],
```


## Configuration

First, you should publish the `config/mobile_verifier.php` config file with:

```
php artisan vendor:publish --provider="Fouladgar\MobileVerifier\ServiceProvider" --tag="config"
```

If you’re using another table name for `users` table or different column name for `mobile` or even `mobile_verification_tokens` table, you can customize their values in config file:

```php
// config/mobile_verifier.php

'user_table'    => 'users',

'mobile_column' => 'mobile',

'token_table'   => 'mobile_verification_tokens',
```

And then migrate the database:
```
php artisan migrate
``` 

The package migration will create a table your application needs to store verification tokens. Also, a `mobile_verified_at` column will be add to your `users` table to show user verification state.

### Model Preparation

To get started, verify that your `App\User` model implements the `Fouladgar\MobileVerifier\Contracts\MustVerifyMobile` contract and use the `Fouladgar\MobileVerifier\Concerns\MustVerifyMobile` trait:

```php
<?php

namespace App;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile as IMustVerifyMobile;
use Fouladgar\MobileVerifier\Concerns\MustVerifyMobile;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements IMustVerifyMobile
{
    use Notifiable, MustVerifyMobile;

    // ...
}
```

### SMS Client

Sending verification notifications via SMS in this package is totally desirable. Before you can send notifications via this package, you need to install or prepare your SMS client class and implements `Fouladgar\MobileVerifier\Contracts\SmsClient` contract. 

```php
<?php

namespace App;

use Fouladgar\MobileVerifier\Contracts\SmsClient;
use Fouladgar\MobileVerifier\Concerns\Payload;

class SMSService implements SmsClient
{
    /**
     * @param Payload $payload
     *
     * @return mixed
     */
    public function sendMessage(Payload $payload)
    {
        // return $this->send($payload->getTo(), $payload->getToken());
    }

    // ...
}
```

## Usage


## Testing
```sh
composer test
```

Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
## Security

If you discover any security related issues, please email fouladgar.dev@gmail.com instead of using the issue tracker.

## License

Laravel-Mobile-Verifier is released under the MIT License. See the bundled
 [LICENSE](https://github.com/mohammad-fouladgar/laravel-mobile-verifier/blob/master/LICENSE)
 file for details.

Built with :heart: for you.
