<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Contracts;

interface MustVerifyMobile
{
    /**
     * Determine if the user has verified their mobile number.
     */
    public function hasVerifiedMobile(): bool;

    /**
     * Mark the given user's mobile as verified.
     */
    public function markMobileAsVerified(): bool;

    /**
     * Send the mobile verification notification.
     */
    public function sendMobileVerifierNotification(string $token): void;

    /**
     * Get the mobile number that should be used for verification.
     */
    public function getMobileForVerification(): string;
}
