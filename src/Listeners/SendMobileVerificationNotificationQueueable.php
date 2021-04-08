<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMobileVerificationNotificationQueueable extends AbstractMobileVerificationListener implements ShouldQueue
{
    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'tries':
                return config('mobile_verifier.queue.tries');
                break;
            case 'timeout':
                return config('mobile_verifier.queue.timeout');
                break;
            case 'connection':
                return config('mobile_verifier.queue.connection');
                break;
            case 'queue':
                return config('mobile_verifier.queue.queue');
                break;
        }
    }
}
