<?php

namespace Fouladgar\MobileVerification;

use Fouladgar\MobileVerification\Listeners\SendMobileVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use function Illuminate\Events\queueable;

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

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(queueable(function (Registered $event) {})
            ->onConnection(config('mobile_verifier.queue.connection'))
            ->onQueue(config('mobile_verifier.queue.queue')));
    }
}
