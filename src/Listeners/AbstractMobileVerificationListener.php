<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Listeners;

use Exception;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Notifications\Notification;

abstract class AbstractMobileVerificationListener extends Notification
{
    public function __construct(protected TokenBrokerInterface $tokenBroker)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if ($user instanceof MustVerifyMobile && ! $user->hasVerifiedMobile()) {
            $this->tokenBroker->sendToken($user);
        }
    }
}
