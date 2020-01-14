<?php

namespace Fouladgar\MobileVerifier\Notifications\Channels;

use Fouladgar\MobileVerifier\Contracts\SmsClient;
use Fouladgar\MobileVerifier\Notifications\Messages\MobileVerificationMessage;
use Illuminate\Notifications\Notification;

class VerificationChannel
{
    /**
     * SmsClient (i.e. Nexmo).
     *
     * @var SmsClient
     */
    protected $smsClient;

    /**
     * VerificationChannel constructor.
     *
     * @param SmsClient $smsClient
     */
    public function __construct(SmsClient $smsClient)
    {
        $this->smsClient = $smsClient;
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
        if (!$to = $notifiable->routeNotificationFor('verification_mobile', $notification)) {
            return;
        }

        /** @var MobileVerificationMessage $message */
        $message = $notification->toMobile($notifiable);

        return $this->smsClient->sendMessage($message->getPayload());
    }
}
