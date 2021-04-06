<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Listeners;


use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMobileVerificationNotificationQueueable implements ShouldQueue
{

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = null;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = null;


    protected TokenBrokerInterface $tokenBroker;

    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        $this->tokenBroker = $tokenBroker;
        $this->queue = config('mobile_verifier.queue.connection');
        $this->connection = config('mobile_verifier.queue.queue');
    }

    /**
     * @throws \Exception
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if ($user instanceof MustVerifyMobile && !$user->hasVerifiedMobile()) {
            $this->tokenBroker->sendToken($user);
        }
    }
}
