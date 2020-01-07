<?php

namespace Fouladgar\MobileVerifier\Notifications\Channels;

use Fouladgar\MobileVerifier\Contracts\SmsClient;
use Illuminate\Notifications\Notification;

class VerificationChannel
{
    /**
     * @var SmsClient
     */
    protected $smsClient;

    /**
     * VerificationChannel constructor.
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
     * @return mixed|void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('verification_mobile', $notification)) {
            return;
        }

        $message = $notification->toVerify($notifiable);

        $payload = [
            'token' => $message->getCode(),
            'to'    => $to
        ];

        return $this->smsClient->sendMessage($payload);
    }
}
