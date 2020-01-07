<?php

namespace Fouladgar\MobileVerifier\Listeners;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Illuminate\Auth\Events\Registered;

class SendMobileVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        if ($event->user instanceof MustVerifyMobile && !$event->user->hasVerifiedMobile()) {
            $event->user->sendMobileVerifierNotification();
        }
    }
}
