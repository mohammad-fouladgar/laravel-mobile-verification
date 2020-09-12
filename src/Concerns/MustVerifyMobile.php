<?php

namespace Fouladgar\MobileVerification\Concerns;

use Fouladgar\MobileVerification\Notifications\VerifyMobile as VerifyMobileNotification;
use Illuminate\Config\Repository;

trait MustVerifyMobile
{
    /**
     * Determine if the user has verified their mobile number.
     */
    public function hasVerifiedMobile(): bool
    {
        return null !== $this->mobile_verified_at;
    }

    /**
     * Mark the given user's mobile as verified.
     */
    public function markMobileAsVerified(): bool
    {
        return $this->forceFill(['mobile_verified_at' => $this->freshTimestamp()])->save();
    }

    /**
     * Send the mobile verification notification.
     */
    public function sendMobileVerifierNotification(string $token): void
    {
        $this->notify(new VerifyMobileNotification($token));
    }

    /**
     * Get the mobile number that should be used for verification.
     */
    public function getMobileForVerification(): string
    {
        return $this->{$this->getMobileField()};
    }

    /**
     * Get the recipients of the given message.
     *
     * @return mixed
     */
    public function routeNotificationForVerificationMobile(): string
    {
        return $this->{$this->getMobileField()};
    }

    /**
     * @return Repository|mixed
     */
    private function getMobileField()
    {
        return config('mobile_verifier.mobile_column', 'mobile');
    }
}
