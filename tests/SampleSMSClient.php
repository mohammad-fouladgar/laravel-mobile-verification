<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\Payload;
use Fouladgar\MobileVerifier\Contracts\SmsClient;

class SampleSMSClient implements SmsClient
{
    public function sendMessage(Payload $payload)
    {
//        return $this->send($payload->getTo(), $payload->getToken());
    }
}
