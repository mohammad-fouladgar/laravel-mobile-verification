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
     * @var array
     */
    protected $arguments;

    /**
     * Create a new event instance.
     *
     * @param MustVerifyMobile $user
     * @param array $arguments
     */
    public function __construct($user, ...$arguments)
    {
        $this->user      = $user;
        $this->arguments = $arguments;
    }
}
