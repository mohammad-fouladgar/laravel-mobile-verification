<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Notifications;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Notifications\Channels\VerificationChannel;
use Fouladgar\MobileVerification\Notifications\Messages\MobileVerificationMessage;
use Illuminate\Notifications\Notification;

class VerifyMobile extends Notification
{
    /** @var string  */
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
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
