<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Events;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Illuminate\Queue\SerializesModels;

class Verified
{
    use SerializesModels;

    public function __construct(public MustVerifyMobile $user, protected array $request)
    {
    }
}
