<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Http\Controllers;

use Fouladgar\MobileVerification\Http\Requests\VerificationRequest;
use Illuminate\Http\Request;

interface BaseVerificationControllerInterface
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Routing\Redirector
     */
    public function verify(VerificationRequest $request);

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Routing\Redirector
     */
    public function resend(Request $request);
}
