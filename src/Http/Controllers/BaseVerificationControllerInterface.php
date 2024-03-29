<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Http\Controllers;

use Fouladgar\MobileVerification\Http\Requests\VerificationRequest;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

interface BaseVerificationControllerInterface
{
    public function verify(VerificationRequest $request): ViewFactory|JsonResponse|RedirectResponse;

    public function resend(Request $request): ViewFactory|JsonResponse|Redirector|RedirectResponse;
}
