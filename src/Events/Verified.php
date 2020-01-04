<?php

namespace Fouladgar\MobileVerification\Events;

use Illuminate\Queue\SerializesModels;

class Verified
{
    use SerializesModels;

    /**
     * The verified user.
     *
     * @var \Fouladgar\MobileVerification\Contracts\MustVerifyMobile
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \Fouladgar\MobileVerification\Contracts\MustVerifyMobile  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
