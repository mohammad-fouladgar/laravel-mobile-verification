# Laravel Mobile Verification

![alt text](./cover.jpg "EloquentBuilder")

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fouladgar/laravel-mobile-verification.svg)](https://packagist.org/packages/fouladgar/laravel-mobile-verification)
![Test Status](https://img.shields.io/github/actions/workflow/status/mohammad-fouladgar/laravel-mobile-verification/run-tests.yml?label=tests)
![Code Style Status](https://img.shields.io/github/actions/workflow/status/mohammad-fouladgar/laravel-mobile-verification/php-cs-fixer.yml?label=code%20style)
![Total Downloads](https://img.shields.io/packagist/dt/fouladgar/laravel-mobile-verification)

## Introduction
Many web applications require users to verify their mobile phone numbers before using the application. Rather than forcing you to re-implement this on each application, this package provides convenient methods for sending and verifying mobile phone verification requests.

## Version Compatibility

Laravel  | LaravelMobileVerification
:---------|:----------
11.0.x        | 4.2.x
10.0.x        | 4.0.x
9.0.x         | 3.0.x
6.0.x to 8.0x | 2.0.x
5.0.x         | 1.2.0

## Installation

You can install the package via composer:

```shell
composer require fouladgar/laravel-mobile-verification
```

## Configuration

To get started, you should publish the `config/mobile_verifier.php` config file with:

```
php artisan vendor:publish --provider="Fouladgar\MobileVerification\ServiceProvider" --tag="config"
```

### Token Storage
After generating a token, we need to store it in a storage. This package supports two drivers: `cache` and `database` which the default driver is `cache`. You may specify which storage driver you would like to be used for saving tokens in your application:
```php
// config/mobile_verifier.php

<?php

return [
    /**
    |Supported drivers: "cache", "database"
    */
    'token_storage' => 'cache',
];
```

##### Database
It means after migrating, a table will be created which your application needs to store verification tokens.

> If you’re using another table name for `users` table or different column name for `mobile` phone or even `mobile_verification_tokens` table, you can customize their values in config file:

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
##### Cache
When using the `cache` driver, the token will be stored in a cache driver configured by your application. In this case, your application performance is more than when using database definitely.

All right! Now you should migrate the database:
```
php artisan migrate
```

Depending on the `token_storage` config, the package migration will create a token table. Also, a `mobile_verified_at` and `mobile` column will be added to your `users` table to show user verification state and store user's mobile phone.

### Model Preparation

In the following, make sure your `User` model implements the `Fouladgar\MobileVerification\Contracts\MustVerifyMobile` contract and use the `Fouladgar\MobileVerification\Concerns\MustVerifyMobile` trait:

```php
<?php

namespace App;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile as IMustVerifyMobile;
use Fouladgar\MobileVerification\Concerns\MustVerifyMobile;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements IMustVerifyMobile
{
    use Notifiable, MustVerifyMobile;

    // ...
}
```

### SMS Client

You can use any SMS service for sending verification messages(it depends on your choice). For sending notifications via this package, first you need to implement the `Fouladgar\MobileVerification\Contracts\SMSClient` contract. This contract requires you to implement `sendMessage` method.

This method will return your SMS service API results via a `Payload` object which contains user **number** and **token** message:

```php
<?php

namespace App;

use Fouladgar\MobileVerification\Contracts\SMSClient;
use Fouladgar\MobileVerification\Notifications\Messages\Payload;

class SampleSMSClient implements SMSClient
{
    protected $SMSService;

    /**
     * @param Payload $payload
     *
     * @return mixed
     */
    public function sendMessage(Payload $payload):mixed
    {
        // preparing SMSService ...

        return $this->SMSService
                 ->send($payload->getTo(), $payload->getToken());
    }

    // ...
}
```
> In above example, `SMSService` can be replaced with your chosen SMS service along with its respective method.

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

At this point, a notification message has been sent to user automatically, and you've done half of the job!

## Routing

This package includes the `Fouladgar\MobileVerification\Http\Controllers\MobileVerificationController` class that contains the necessary logic to send verification token and verify users.

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

> Notice: You should choose a long with middleware which you are going to use them for above APIs through set it in config file:

```php
// config/mobile_verifier.php

<?php

return [

    'middleware' => ['auth:sanctum'],

    //...
];
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

Also, this package allows you to override default controller. To achieve this, you can extend your controller from `Fouladgar\MobileVerification\Http\Controllers\BaseVerificationController` and set your controller namespace in config file:

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

use Fouladgar\MobileVerification\Http\Controllers\BaseVerificationController;

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

Route middleware can be used to only allow verified users to access a given route. This package ships with a verified middleware, which is defined at `Fouladgar\MobileVerification\Http\Middleware`. Since this middleware is already registered in your application's HTTP kernel, all you need to do is attach the middleware to a route definition:

```php
Route::get('profile', function () {
    // Only verified users may enter...
})->middleware('mobile.verified');
```

## Using Queue

By default, this package does not process sending verification messages in the queue. But if you want your sending messages to be queued, you may change `connection` value from `sync` to your preferred queue connection.
And be sure to config your queue connection in your `.env` file.
You are allowed to to change other queue settings here in the config.

```php
// config/mobile_verifier.php
<?php

return [
     /**
     | Supported drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
     */
    'queue' =>  [
       'connection' => 'sync',
       'queue' => 'default',
       'tries' => 3,
       'timeout' => 60,
    ]
];
```

## Translates and Views

To publish translation file you may use this command:

```
php artisan vendor:publish --provider="Fouladgar\MobileVerification\ServiceProvider" --tag="lang"
```

If you are not using AJAX requests, you should have some views which we provided you some information through session variables. In case of errors, you just need to use laravel default `$errors` variable. In case of successful verification, you can use `mobileVerificationVerified` variable and for successful resend verification you may use `mobileVerificationResend` variable. These variables contain messages which you can customize in provided language file:

```php
// lang/vendor/MobileVerification/en/mobile_verification.php

<?php

return [
    'successful_verification' => 'Your mobile has been verified successfully.',
    'successful_resend'       => 'The token has been resent successfully.',
    'already_verified'        => 'Your mobile already has been verified.',
    //etc...
];
```

## Event

This package dispatch an event during the mobile verification process. You may attach listeners to this event in your `EventServiceProvider`:

```php
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    'Fouladgar\MobileVerification\Events\Verified' => [
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

Laravel-Mobile-Verification is released under the MIT License. See the bundled
 [LICENSE](https://github.com/mohammad-fouladgar/laravel-mobile-verification/blob/master/LICENSE)
 file for details.

Built with :heart: for you.
