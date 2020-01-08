<?php

namespace Fouladgar\MobileVerifier\Contracts;

interface TokenBrokerInterface
{
    /**
     * Send token via notification
     *
     * @param MustVerifyMobile $user
     * @return void
     */
    public function sendToken(MustVerifyMobile $user): void;
}
