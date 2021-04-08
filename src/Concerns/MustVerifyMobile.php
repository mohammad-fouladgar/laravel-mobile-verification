<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Concerns;

use Fouladgar\MobileVerification\Notifications\VerifyMobile;

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
     */
    private function appendMobileFieldToFillableAttributes(): void
    {
        $mobileFiled = $this->getMobileField();

        if (! in_array($mobileFiled, $this->fillable, true)) {
            $this->fillable = array_merge($this->fillable, [$mobileFiled]);
        }
    }

    /**
     * Get mobile phone field name.
     */
    private function getMobileField(): string
    {
        return config('mobile_verifier.mobile_column', 'mobile');
    }

    /**
     * Determine if the user has verified their mobile number.
     */
    public function hasVerifiedMobile(): bool
    {
        return $this->mobile_verified_at !== null;
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
        $this->notify(new VerifyMobile($token));
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
     */
    public function routeNotificationForVerificationMobile(): string
    {
        return $this->{$this->getMobileField()};
    }
}
