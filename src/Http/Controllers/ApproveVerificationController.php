<?php

use Renter\Payment\Http\Requests\VerificationRequest;
use Fouladgar\MobileVerifier\Events\Verified;

class ApproveVerificationController
{
    /**
     * @param VerificationRequest $request
     */
    public function __invoke(VerificationRequest $request)
    {
        $user = $request->user();

        $user->markMobileAsVerified();

        event(new Verified($user, $request));
    }
}
