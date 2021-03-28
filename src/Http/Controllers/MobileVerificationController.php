<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Http\Controllers;

class MobileVerificationController extends BaseVerificationController
{
    /*
    |--------------------------------------------------------------------------
    | Mobile Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling mobile verification for any
    | user that recently registered with the application. Tokens may also
    | be re-sent if the user didn't receive the original token message.
    |
    */

    /**
     * Where to redirect users after verification.
     */
    protected string $redirectTo = '/home';
}
