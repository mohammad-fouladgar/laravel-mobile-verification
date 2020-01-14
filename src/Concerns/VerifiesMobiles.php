<?php

namespace Fouladgar\MobileVerifier\Concerns;

use Fouladgar\MobileVerifier\Events\Verified;
use Fouladgar\MobileVerifier\Exceptions\InvalidTokenException;
use Fouladgar\MobileVerifier\Http\Requests\VerificationRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

trait VerifiesMobiles
{
    use RedirectsUsers;

    /**
     * @param VerificationRequest $request
     *
     * @return Factory|JsonResponse|Redirector
     */
    public function verify(VerificationRequest $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson() ? $this->unprocessableEntity() : redirect($this->redirectPath());
        }

        try {
            $this->tokenBroker->verifyToken($user, $request->token);
        } catch (InvalidTokenException $e) {
            return $request->expectsJson()
                ? response()->json(['message' => $e->getMessage()], $e->getCode())
                : view('MobileVerifier::auth.mobile_verify')->with('mobileVerificationError', $e->getMessage());
        }

        event(new Verified($user, $request->all()));

        return $request->expectsJson()
            ? $this->successMessage()
            : redirect($this->redirectPath())->with('mobileVerificationVerified', true);
    }

    /**
     * @param Request $request
     *
     * @return Factory|JsonResponse|Redirector
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson() ? $this->unprocessableEntity() : redirect($this->redirectPath());
        }

        $this->tokenBroker->sendToken($user);

        return $request->expectsJson() ? $this->successMessage() :
            view('MobileVerifier::auth.mobile_verify')->with('mobileVerificationResend', true);
    }

    /**
     * @return JsonResponse
     */
    protected function successMessage(): JsonResponse
    {
        return response()->json(['message' => __('mobile_verifier.successful_verification')], 200);
    }

    /**
     * @return JsonResponse
     */
    protected function unprocessableEntity(): JsonResponse
    {
        return response()->json(['message' => __('mobile_verifier.already_verified')], 422);
    }
}
