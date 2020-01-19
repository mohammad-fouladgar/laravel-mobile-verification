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

To get started, you should publish the `config/mobile_verifier.php` config file with:

```
php artisan vendor:publish --provider="Fouladgar\MobileVerifier\ServiceProvider" --tag="config"
```

If you’re using another table name for `users` table or different column name for `mobile` or even `mobile_verification_tokens` table, you can customize their values in config file:

```php
// config/mobile_verifier.php

<?php

return [

    'user_table'    => 'users',
    
    'mobile_column' => 'mobile',
    
    'token_table'   => 'mobile_verification_tokens',

    //...
];
```

And then migrate the database:
```
php artisan migrate
``` 

The package migration will create a table your application needs to store verification tokens. Also, a `mobile_verified_at` column will be add to your `users` table to show user verification state.

### Model Preparation

In the following, verify that your `User` model implements the `Fouladgar\MobileVerifier\Contracts\MustVerifyMobile` contract and use the `Fouladgar\MobileVerifier\Concerns\MustVerifyMobile` trait:

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

You can use any SMS service for sending verification messages such as `Nexmo`, `Twilio` or etc. For sending notifications via this package, first you need to implement the `Fouladgar\MobileVerifier\Contracts\SMSClient` contract. This contract requires you to implement `sendMessage` method. 

This method will return your SMS service api result via a `Payload` object which contains user **number** and **token** message:

```php
<?php

namespace App;

use Fouladgar\MobileVerifier\Contracts\SMSClient;
use Fouladgar\MobileVerifier\Concerns\Payload;

class SampleSMSClient implements SMSClient
{
    /**
     * @param Payload $payload
     *
     * @return mixed
     */
    public function sendMessage(Payload $payload)
    {
        // return $this->NexmoService->send($payload->getTo(), $payload->getToken());
    }

    // ...
}
```
> :information_source: In above example, `NexmoService` can be replaced with your chosen SMS service along with its respective method.

Next, you should set the your `SMSClient` class in config file:

```php
// config/mobile_verifier.php

<?php

return [

  'sms_client' => App\SampleSMSClient::class, 
    
  //...
];
```

## Usage

Now you are ready for sending a verification message! You just need to dispatch the `Illuminate\Auth\Events\Registered` event after registering user:

```php
<?php

use Illuminate\Auth\Events\Registered;

// Register user

 event(new Registered($user));

//...
```

At this point, a notification message has been sent to user and you've done half of the job! 


## Routing

This package includes the `Fouladgar\MobileVerifier\Http\Controllers\MobileVerificationController` class that contains the necessary logic to send verification token and verify users.

### Verify

In order to use this route, you should send `token` message of an authenticated user (along with any desired data) to this route `/auth/mobile/verify`:

```
curl -X POST \
      http://example.com/auth/mobile/verify \
      -H 'Accept: application/json' \
      -H 'Authorization: JWT_TOKEN' \
      -F token=12345
```

### Resend

If you need to resend a verification message, you can use this route `/auth/mobile/resend` for an authenticated user:

```
curl -X POST \
      http://example.com/auth/mobile/resend \
      -H 'Accept: application/json' \
      -H 'Authorization: JWT_TOKEN'
```

### Customize Routes and Controller

In order to change default routes prefix or routes themselves, you can customize them in config file:

```php
// config/mobile_verifier.php

<?php

return [

    'routes_prefix' => 'auth',

    'routes' => [
        'verify' => '/mobile/verify',
        'resend' => '/mobile/resend',
    ],

    //...
];
```

Also this package allows you to override default controller. To achieve this, you can extend your controller from `Fouladgar\MobileVerifier\Http\Controllers\BaseVerificationController` and set your controller namespace in config file:

```php
// config/mobile_verifier.php

<?php

return [

    'controller_namespace' => 'App\Http\Controllers',

    //...
];
```
> **Note:** You can only change controller namespace and name of the controller should remain as package default controller (`MobileVerificationController`)

```php
<?php

namespace App\Http\Controllers;

use Fouladgar\MobileVerifier\Http\Controllers\BaseVerificationController;

class MobileVerificationController extends BaseVerificationController
{
    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';
}

```

> **Important note**: If you set header request to `Accept:application/json`, the response will be in Json format, otherwise the user will automatically be redirected to `/home`. You can customize the post verification redirect location by defining a `redirectTo` method or property on the `MobileVerificationController`.

## Protecting Routes

Route middleware can be used to only allow verified users to access a given route. This package ships with a verified middleware, which is defined at `Fouladgar\MobileVerifier\Http\Middleware`. Since this middleware is already registered in your application's HTTP kernel, all you need to do is attach the middleware to a route definition:

```php
Route::get('profile', function () {
    // Only verified users may enter...
})->middleware('mobile.verified');
```

## Views and Translates

To generate all of the necessary views and translates for mobile verification, you may publish assets with:

```
php artisan vendor:publish --provider="Fouladgar\MobileVerifier\ServiceProvider" --tag="assets"
```

The mobile verification view is placed in `resources/views/vendor/MobileVerifier/auth/mobile_verify.blade.php` and the lang is placed in `resources/lang/en/mobile_verifier.php`

## Event

This package dispatch an event during the mobile verification process. You may attach listeners to this event in your `EventServiceProvider`:

```php
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    'Fouladgar\MobileVerifier\Events\Verified' => [
        'App\Listeners\LogVerifiedUser',
    ],
];
```

## Testing
```sh
composer test
```

## Changelog

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
