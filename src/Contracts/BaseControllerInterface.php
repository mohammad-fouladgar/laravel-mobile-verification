<?php

namespace Fouladgar\MobileVerifier\Contracts;

use Fouladgar\MobileVerifier\Http\Requests\VerificationRequest;
use Illuminate\Http\Request;

interface BaseControllerInterface
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
