<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Events;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Illuminate\Queue\SerializesModels;

class Verified
{
    use SerializesModels;

    /**
     * The verified user.
     */
    public MustVerifyMobile $user;

    /**
     * The validated request.
     */
    protected array $request;

    public function __construct(MustVerifyMobile $user, array $request)
    {
        $this->user = $user;
        $this->request = $request;
    }
}
