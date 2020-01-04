<?php

namespace Fouladgar\MobileVerification\Notifications;

use Illuminate\Notifications\Notification;
use Fouladgar\MobileVerification\Notifications\Channels\VerificationChannel;
use Fouladgar\MobileVerification\Notifications\Messages\MobileVerificationMessage;

class VerifyMobile extends Notification
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    // public static $toMailCallback;

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


    public function toVerification($notifiable)
    {
        return (new MobileVerificationMessage());
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // $verificationUrl = $this->verificationUrl($notifiable);

        // if (static::$toMailCallback) {
        //     return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        // }

        // return (new MailMessage)
        //     ->subject(Lang::get('Verify Email Address'))
        //     ->line(Lang::get('Please click the button below to verify your email address.'))
        //     ->action(Lang::get('Verify Email Address'), $verificationUrl)
        //     ->line(Lang::get('If you did not create an account, no further action is required.'));
    }


    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    // public static function toMailUsing($callback)
    // {
    //     static::$toMailCallback = $callback;
    // }
}
