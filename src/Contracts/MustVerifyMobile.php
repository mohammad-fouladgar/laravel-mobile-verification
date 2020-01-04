<?php

namespace Fouladgar\MobileVerification\Contracts;

interface MustVerifyMobile
{
    /**
     * Determine if the user has verified their mobile number.
     *
     * @return bool
     */
    public function hasVerifiedMobile();

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified();

    /**
     * Send the mobile verification notification.
     *
     * @return void
     */
    public function sendMobileVerificationNotification();

    /**
     * Get the mobile number that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification();
}