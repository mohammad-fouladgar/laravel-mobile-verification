<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Notifications\Channels;

use Fouladgar\MobileVerification\Contracts\SMSClient;
use Fouladgar\MobileVerification\Notifications\Messages\MobileVerificationMessage;
use Illuminate\Notifications\Notification;

class VerificationChannel
{
    public function __construct(private SMSClient $SMSClient)
    {
    }

    public function send(mixed $notifiable, Notification $notification): mixed
    {
        if (! $notifiable->routeNotificationFor('verification_mobile', $notification)) {
            return null;
        }

        /** @var MobileVerificationMessage $message */
        $message = $notification->toMobile($notifiable);

        return $this->SMSClient->sendMessage($message->getPayload());
    }
}
