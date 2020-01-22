<?php

namespace Fouladgar\MobileVerifier\Http\Controllers;

use Fouladgar\MobileVerifier\Http\Requests\VerificationRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

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
