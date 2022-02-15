<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Contracts\SMSClient;
use Fouladgar\MobileVerification\Notifications\Messages\Payload;

class SampleSMSClient implements SMSClient
{
    public function sendMessage(Payload $payload): mixed
    {
        return null;
//        return $this->send($payload->getTo(), $payload->getToken());
    }
}
