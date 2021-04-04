<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Notifications\Factory;

use Fouladgar\MobileVerification\Notifications\VerifyMobileOnQueue;
use Fouladgar\MobileVerification\Notifications\VerifyMobile;

class VerifyMobileNotificationFactory
{
    /**
     * Create notification class
     *
     * @param string $token
     *
     * @return VerifyMobile|VerifyMobileOnQueue
     */
    public static function create(string $token)
    {
        $use_queue = config('mobile_verifier.use_queue', false);
        if ($use_queue) {
            return new VerifyMobileOnQueue($token);
        }
        return new VerifyMobile($token);
    }
}
