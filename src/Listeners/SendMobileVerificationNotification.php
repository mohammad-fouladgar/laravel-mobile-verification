<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Listeners;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Illuminate\Auth\Events\Registered;

class SendMobileVerificationNotification
{
    protected TokenBrokerInterface $tokenBroker;

    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        $this->tokenBroker = $tokenBroker;
    }

    /**
     * @throws \Exception
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if ($user instanceof MustVerifyMobile && ! $user->hasVerifiedMobile()) {
            $this->tokenBroker->sendToken($user);
        }
    }
}
