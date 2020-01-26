<?php

namespace Fouladgar\MobileVerification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\Factory;
use Fouladgar\MobileVerification\Http\Requests\VerificationRequest;

interface BaseVerificationControllerInterface
{
    /**
     * @param VerificationRequest $request
     *
     * @return Factory|JsonResponse|Redirector
     */
    public function verify(VerificationRequest $request);

    /**
     * @param Request $request
     *
     * @return Factory|JsonResponse|Redirector
     */
    public function resend(Request $request);
}
