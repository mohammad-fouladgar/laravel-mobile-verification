<?php

namespace Fouladgar\MobileVerifier\Concerns;

use Fouladgar\MobileVerifier\Notifications\VerifyMobile;

trait MustVerifyMobile
{
    /**
     * Determine if the user has verified their mobile number.
     *
     * @return bool
     */
    public function hasVerifiedMobile(): bool
    {
        return $this->mobile_verified_at !== null;
    }

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified(): bool
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the mobile verification notification.
     *
     * @return void
     */
    public function sendMobileVerifierNotification(): void
    {
        $this->notify(new VerifyMobile());
    }

    /**
     * Get the mobile number that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification(): string
    {
        return $this->mobile;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function routeNotificationForVerificationMobile()
    {
        return $this->mobile;
    }
}
