<?php

namespace Fouladgar\MobileVerifier\Events;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Renter\Payment\Http\Requests\VerificationRequest;
use Illuminate\Queue\SerializesModels;

class Verified
{
    use SerializesModels;

    /**
     * The verified user.
     *
     * @var MustVerifyMobile
     */
    public $user;

    /**
     * The validated request
     *
     * @var VerificationRequest
     */
    protected $request;

    /**
     * Create a new event instance
     *
     * @param $user
     * @param VerificationRequest $request
     */
    public function __construct($user, VerificationRequest $request)
    {
        $this->user    = $user;
        $this->request = $request;
    }
}
