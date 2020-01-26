<?php

namespace Fouladgar\MobileVerification;

use Illuminate\Auth\Events\Registered;
use Fouladgar\MobileVerification\Listeners\SendMobileVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        ],
    ];
}
