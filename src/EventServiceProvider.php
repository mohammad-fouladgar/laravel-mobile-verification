<?php

namespace Fouladgar\MobileVerifier;

use Fouladgar\MobileVerifier\Listeners\SendMobileVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Registered;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendMobileVerificationNotification::class,
        ]
    ];
}
