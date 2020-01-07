<?php

namespace Fouladgar\MobileVerifier\Notifications;

use Illuminate\Notifications\Notification;
use Fouladgar\MobileVerifier\Notifications\Channels\VerificationChannel;
use Fouladgar\MobileVerifier\Notifications\Messages\MobileVerificationMessage;

class VerifyMobile extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [VerificationChannel::class];
    }

    public function toVerify($notifiable)
    {
        return (new MobileVerificationMessage())->code('24155');
    }
}
