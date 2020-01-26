<?php

namespace Fouladgar\MobileVerification\Tokens;

use Exception;
use Throwable;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;

interface TokenBrokerInterface
{
    /**
     * Send token via notification.
     *
     * @param MustVerifyMobile $user
     *
     * @throws Exception
     *
     * @return void
     */
    public function sendToken(MustVerifyMobile $user): void;

    /**
     * @param MustVerifyMobile $user
     * @param $token
     *
     * @throws Throwable
     *
     * @return bool
     */
    public function verifyToken(MustVerifyMobile $user, $token): bool;
}
