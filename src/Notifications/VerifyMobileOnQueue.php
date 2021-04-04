<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class VerifyMobileOnQueue extends VerifyMobile implements ShouldQueue
{
    use Queueable;
}
