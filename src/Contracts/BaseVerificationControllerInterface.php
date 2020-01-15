<?php

namespace Fouladgar\MobileVerifier\Contracts;

use Fouladgar\MobileVerifier\Http\Requests\VerificationRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;

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
