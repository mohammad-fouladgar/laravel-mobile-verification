<?php

namespace Fouladgar\MobileVerifier\Listeners;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Illuminate\Auth\Events\Registered;
use Exception;

class SendMobileVerificationNotification
{
    /**
     * @var TokenBrokerInterface
     */
    protected $tokenBroker;

    /**
     * SendMobileVerificationNotification constructor.
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
     * @return void
     * @throws Exception
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if ($user instanceof MustVerifyMobile && !$user->hasVerifiedMobile()) {
            $this->tokenBroker->sendToken($user);
        }
    }
}
