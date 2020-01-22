<?php

namespace Fouladgar\MobileVerifier\Tokens;

use Exception;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Throwable;

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
