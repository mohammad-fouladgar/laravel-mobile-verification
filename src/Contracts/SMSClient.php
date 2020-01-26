<?php

namespace Fouladgar\MobileVerification\Contracts;

use Fouladgar\MobileVerification\Notifications\Messages\Payload;

interface SMSClient
{
    /**
     * @param Payload $payload
     *
     * @return mixed
     */
    public function sendMessage(Payload $payload);
}
