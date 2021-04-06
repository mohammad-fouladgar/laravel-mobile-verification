<?php

namespace Fouladgar\MobileVerification;

use Fouladgar\MobileVerification\Listeners\SendMobileVerificationNotification;
use Fouladgar\MobileVerification\Listeners\SendMobileVerificationNotificationQueueable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        if (config('mobile_verifier.queue.connection') == 'sync') {
            Event::listen(Registered::class, [SendMobileVerificationNotification::class, 'handle']);
        } else {
            Event::listen(Registered::class, [SendMobileVerificationNotificationQueueable::class, 'handle']);
        }
    }
}
