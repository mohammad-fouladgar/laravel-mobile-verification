<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Contracts;

use Fouladgar\MobileVerification\Notifications\Messages\Payload;

interface SMSClient
{
    /**
     * @return mixed
     */
    public function sendMessage(Payload $payload);
}
