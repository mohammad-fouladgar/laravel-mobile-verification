<?php

namespace Fouladgar\MobileVerification\Concerns;

use Illuminate\Http\JsonResponse;

trait Responses
{
    /**
     * @return JsonResponse
     */
    protected function successMessage(): JsonResponse
    {
        return response()->json(['message' => __('MobileVerification::mobile_verifier.successful_verification')], 200);
    }

    /**
     * @return JsonResponse
     */
    protected function successResendMessage(): JsonResponse
    {
        return response()->json(['message' => __('MobileVerification::mobile_verifier.successful_resend')], 200);
    }

    /**
     * @return JsonResponse
     */
    protected function unprocessableEntity(): JsonResponse
    {
        return response()->json(['message' => __('MobileVerification::mobile_verifier.already_verified')], 422);
    }
}
