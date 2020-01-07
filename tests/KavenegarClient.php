<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\SmsClient;

class KavenegarClient extends SmsClient
{
    public function sendMessage($payload)
    {
        dd($payload);
        return $payload;
    }
}
