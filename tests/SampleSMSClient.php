<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Notifications\Messages\Payload;
use Fouladgar\MobileVerifier\Contracts\SMSClient;

class SampleSMSClient implements SMSClient
{
    public function sendMessage(Payload $payload)
    {
//        return $this->send($payload->getTo(), $payload->getToken());
    }
}
