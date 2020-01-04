<?php

namespace Fouladgar\MobileVerification\Notifications\Channels;

// use App\SMS\SmsIrClient;
use Illuminate\Notifications\Notification;

class VerificationChannel
{
    /**
     * The Nexmo client instance.
     *
     * @var \App\SMS\SmsIrClient
     */
    // protected $sms;

    /**
     * Create a new SMS channel instance.
     *
     * @param \App\SMS\SmsIrClient $sms
     */
    // public function __construct(SmsIrClient $sms)
    // {
    //     $this->sms = $sms;
    // }

    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return \Nexmo\Message\Message
     */
    public function send($notifiable, Notification $notification)
    {
        // if (!$to = $notifiable->routeNotificationFor('verification_mobile', $notification)) {
        //     return;
        // }

        // $message = $notification->toVerificationSms($notifiable);

        // return $this->sms->fastSendMessage([
        //       'VerificationCode'=> $message->code,
        // ], $message->template_id, $to);
    }
}
