<?php

namespace Fouladgar\MobileVerifier\Concerns;

abstract class SmsClient
{
    abstract public function sendMessage(array $payload);
}
