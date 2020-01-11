<?php

namespace Fouladgar\MobileVerifier\Contracts;

use Exception;
use Throwable;

interface TokenBrokerInterface
{
    /**
     * Send token via notification
     *
     * @param MustVerifyMobile $user
     * @return void
     * @throws Exception
     */
    public function sendToken(MustVerifyMobile $user): void;

    /**
     * @param MustVerifyMobile $user
     * @param $token
     * @return bool
     * @throws Throwable
     */
    public function verifyToken(MustVerifyMobile $user, $token): bool;
}
