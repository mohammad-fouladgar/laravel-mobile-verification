<?php

namespace Fouladgar\MobileVerification\Notifications\Channels;

use Fouladgar\MobileVerification\Contracts\SMSClient;
use Fouladgar\MobileVerification\Notifications\Messages\MobileVerificationMessage;
use Illuminate\Notifications\Notification;

class VerificationChannel
{
    /**
     * SMSClient (i.e. Nexmo).
     *
     * @var SMSClient
     */
    protected $SMSClient;

    /**
     * VerificationChannel constructor.
     *
     * @param SMSClient $SMSClient
     */
    public function __construct(SMSClient $SMSClient)
    {
        $this->SMSClient = $SMSClient;
    }

    /**
     * Send the given notification.
     *
     * @param $notifiable
     * @param Notification $notification
     *
     * @return mixed|void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $notifiable->routeNotificationFor('verification_mobile', $notification)) {
            return;
        }

        /** @var MobileVerificationMessage $message */
        $message = $notification->toMobile($notifiable);

        return $this->SMSClient->sendMessage($message->getPayload());
    }
}
