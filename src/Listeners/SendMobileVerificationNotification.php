<?php

namespace Fouladgar\MobileVerifier\Listeners;

use Exception;
use Illuminate\Auth\Events\Registered;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerifier\Tokens\TokenBrokerInterface;

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

        if ($user instanceof MustVerifyMobile && ! $user->hasVerifiedMobile()) {
            $this->tokenBroker->sendToken($user);
        }
    }
}
