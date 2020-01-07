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
     * Create a new event instance.
     *
     * @param MustVerifyMobile $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
