<?php

namespace Fouladgar\MobileVerifier\Events;

use Illuminate\Queue\SerializesModels;

class Verified
{
    use SerializesModels;

    /**
     * The verified user.
     *
     * @var \Fouladgar\MobileVerifier\Contracts\MustVerifyMobile
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \Fouladgar\MobileVerifier\Contracts\MustVerifyMobile  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
