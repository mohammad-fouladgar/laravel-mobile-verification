<?php

namespace Fouladgar\MobileVerification\Listeners;

use Exception;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Illuminate\Auth\Events\Registered;

class SendMobileVerificationNotification
{
    /**
     * @var TokenBrokerInterface
     */
    protected $tokenBroker;

    /**
     * SendMobileVerificationNotification constructor.
     *
     * @param TokenBrokerInterface $tokenBroker
     */
    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        $this->tokenBroker = $tokenBroker;
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     *
     * @throws Exception
     *
     * @return void
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if ($user instanceof MustVerifyMobile && !$user->hasVerifiedMobile()) {
            $this->tokenBroker->sendToken($user);
        }
    }
}
