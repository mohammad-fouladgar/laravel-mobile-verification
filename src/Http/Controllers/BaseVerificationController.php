<?php

namespace Fouladgar\MobileVerification\Http\Controllers;

use Illuminate\Routing\Controller;
use Fouladgar\MobileVerification\Concerns\VerifiesMobiles;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;

abstract class BaseVerificationController extends Controller implements BaseVerificationControllerInterface
{
    use VerifiesMobiles;

    /**
     * @var TokenBrokerInterface
     */
    protected $tokenBroker;

    /**
     * Create a new controller instance.
     *
     * @param TokenBrokerInterface $tokenBroker
     *
     * @return void
     */
    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        $this->middleware(config('mobile_verifier.middleware', ['web', 'auth']));

        $throttle = config('mobile_verifier.throttle', 10);

        $this->middleware("throttle:$throttle,1")->only('verify', 'resend');

        $this->tokenBroker = $tokenBroker;
    }
}
