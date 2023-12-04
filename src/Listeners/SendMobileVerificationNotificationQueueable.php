<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

class SendMobileVerificationNotificationQueueable extends AbstractMobileVerificationListener implements ShouldQueue
{
    public function __get(string $name): mixed
    {
        return match ($name) {
            'tries' => config('mobile_verifier.queue.tries'),
            'timeout' => config('mobile_verifier.queue.timeout'),
            'connection' => config('mobile_verifier.queue.connection'),
            'queue' => config('mobile_verifier.queue.queue'),
        };
    }
}
