<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Contracts\SmsClient;

class KavenegarClient implements SmsClient
{
    public function sendMessage(array $payload)
    {
        dd($payload);
        return $payload;
    }
}
