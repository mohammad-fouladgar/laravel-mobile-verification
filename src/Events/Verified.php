<?php

namespace Fouladgar\MobileVerifier\Events;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
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
     * The validated request.
     *
     * @var array
     */
    protected $request;

    /**
     * Create a new event instance.
     *
     * @param MustVerifyMobile $user
     * @param array            $request
     */
    public function __construct(MustVerifyMobile $user, array $request)
    {
        $this->user = $user;
        $this->request = $request;
    }
}
