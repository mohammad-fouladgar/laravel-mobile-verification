<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Exception;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Throwable;

interface TokenBrokerInterface
{
    /**
     * Send token via notification.
     *
     * @throws Exception
     */
    public function sendToken(MustVerifyMobile $user): void;

    /**
     * @throws Throwable
     */
    public function verifyToken(MustVerifyMobile $user, string $token): bool;

    public function tokenExists(MustVerifyMobile $user, string $token): bool;

    public function getLatestSentAt(MustVerifyMobile $user, string $token): string;
}
