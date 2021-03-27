<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Http\Controllers;

use Fouladgar\MobileVerification\Concerns\VerifiesMobiles;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Illuminate\Routing\Controller;

abstract class BaseVerificationController extends Controller implements BaseVerificationControllerInterface
{
    use VerifiesMobiles;

    protected TokenBrokerInterface $tokenBroker;

    /**
     * Create a new controller instance.
     */
    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        $this->middleware(config('mobile_verifier.middleware', ['web', 'auth']));

        $throttle = config('mobile_verifier.throttle', 10);

        $this->middleware("throttle:$throttle,1")->only('verify', 'resend');

        $this->tokenBroker = $tokenBroker;
    }
}
