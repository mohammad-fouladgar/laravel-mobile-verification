<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;

interface TokenBrokerInterface
{
    /**
     * Send token via notification.
     *
     * @throws \Exception
     */
    public function sendToken(MustVerifyMobile $user): void;

    /**
     * @throws \Throwable
     */
    public function verifyToken(MustVerifyMobile $user, string $token): bool;
}
