<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Concerns;

use Illuminate\Http\JsonResponse;

trait Responses
{
    protected function successMessage(): JsonResponse
    {
        return response()->json(['message' => __('MobileVerification::mobile_verifier.successful_verification')]);
    }

    protected function successResendMessage(): JsonResponse
    {
        return response()->json(['message' => __('MobileVerification::mobile_verifier.successful_resend')]);
    }

    protected function unprocessableEntity(): JsonResponse
    {
        return response()->json(['message' => __('MobileVerification::mobile_verifier.already_verified')], 422);
    }
}
