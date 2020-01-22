<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Contracts\SMSClient;
use Fouladgar\MobileVerifier\Notifications\Messages\Payload;

class SampleSMSClient implements SMSClient
{
    public function sendMessage(Payload $payload)
    {
//        return $this->send($payload->getTo(), $payload->getToken());
    }
}
