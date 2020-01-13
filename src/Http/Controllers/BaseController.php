<?php

namespace Fouladgar\MobileVerifier\Http\Controllers;

use Fouladgar\MobileVerifier\Concerns\VerifiesMobiles;
use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
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
     * @return void
     */
    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        $this->middleware('auth');

        // TODO: set throttle from config.
        $this->middleware('throttle:6,1')->only('verify', 'resend');

        $this->tokenBroker = $tokenBroker;
    }
}
