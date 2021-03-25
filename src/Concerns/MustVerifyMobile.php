<?php

namespace Fouladgar\MobileVerification\Concerns;

use Fouladgar\MobileVerification\Notifications\VerifyMobile as VerifyMobileNotification;

trait MustVerifyMobile
{
    /**
     * @inheritDoc
     */
    public function getFillable()
    {
        $this->appendMobileFieldToFillableAttributes();

        return $this->fillable;
    }

    /**
     * Append mobile filed to fillable attributes for model.
     *
     * @return void
     */
    private function appendMobileFieldToFillableAttributes()
    {
        $mobileFiled = $this->getMobileField();

        if (! in_array($mobileFiled, $this->fillable)) {
            $this->fillable = array_merge($this->fillable, [$mobileFiled]);
        }
    }

    /**
     * Get mobile phone field name.
     *
     * @return string
     */
    public function getMobileField(): string
    {
        return config('mobile_verifier.mobile_column', 'mobile');
    }

    /**
     * Determine if the user has verified their mobile number.

     * @return bool
     */
    public function hasVerifiedMobile(): bool
    {
        return null !== $this->mobile_verified_at;
    }

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
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
     *
     * @return string
     */
    public function getMobileForVerification(): string
    {
        return $this->{$this->getMobileField()};
    }

    /**
     * Get the recipients of the given message.
     *
     * @return string
     */
    public function routeNotificationForVerificationMobile(): string
    {
        return $this->{$this->getMobileField()};
    }
}
