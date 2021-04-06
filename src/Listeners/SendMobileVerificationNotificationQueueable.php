<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Listeners;

use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMobileVerificationNotificationQueueable extends AbstractMobileVerificationListener implements ShouldQueue
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


    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        parent::__construct($tokenBroker);
        $this->queue = config('mobile_verifier.queue.connection');
        $this->connection = config('mobile_verifier.queue.queue');
    }
}
