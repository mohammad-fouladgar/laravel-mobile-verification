<?php

namespace Fouladgar\MobileVerifier\Notifications\Channels;

use Fouladgar\MobileVerifier\Concerns\SmsClient;
use Illuminate\Notifications\Notification;

class VerificationChannel
{
    

    protected $smsClient;

    /**
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
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('verification_mobile', $notification)) {
            return;
        }

        $message = $notification->toVerify($notifiable);

        $payload = [
            'token'=>$message->getCode(),
            'to'=>$to

        ];

        return $this->smsClient->sendMessage($payload);

    }
}
