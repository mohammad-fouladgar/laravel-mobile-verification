<?php

namespace Fouladgar\MobileVerification\Events;

use Illuminate\Queue\SerializesModels;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;

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
        $this->user    = $user;
        $this->request = $request;
    }
}
