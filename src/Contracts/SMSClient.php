<?php

namespace Fouladgar\MobileVerifier\Contracts;

use Fouladgar\MobileVerifier\Concerns\Payload;

interface SMSClient
{
    /**
     * @param Payload $payload
     *
     * @return mixed
     */
    public function sendMessage(Payload $payload);
}
