<?php

namespace Fouladgar\MobileVerifier\Contracts;

interface MustVerifyMobile
{
    /**
     * Determine if the user has verified their mobile number.
     *
     * @return bool
     */
    public function hasVerifiedMobile(): bool;

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified(): bool;

    /**
     * Send the mobile verification notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendMobileVerifierNotification(string $token): void;

    /**
     * Get the mobile number that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification(): string;
}
