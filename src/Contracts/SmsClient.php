<?php

namespace Fouladgar\MobileVerifier\Contracts;

interface SmsClient
{
    /**
     * @param array $payload
     * @return mixed
     */
    public function sendMessage(array $payload);
}
