<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Events;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Illuminate\Queue\SerializesModels;

class Verified
{
    use SerializesModels;

    /** @var MustVerifyMobile */
    public $user;

    /**
     * The validated request.
     *
     * @var array
     */
    protected $request;

    public function __construct(MustVerifyMobile $user, array $request)
    {
        $this->user = $user;
        $this->request = $request;
    }
}
