<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Notifications;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Notifications\Channels\VerificationChannel;
use Fouladgar\MobileVerification\Notifications\Messages\MobileVerificationMessage;
use Illuminate\Notifications\Notification;

class VerifyMobile extends Notification
{
    public function __construct(public string $token)
    {
    }

    public function via(mixed $notifiable): array|string
    {
        return [VerificationChannel::class];
    }

    /**
     * Build the mobile representation of the notification.
     */
    public function toMobile(MustVerifyMobile $notifiable): MobileVerificationMessage
    {
        return (new MobileVerificationMessage())->to($notifiable->getMobileForVerification())->token($this->token);
    }
}
