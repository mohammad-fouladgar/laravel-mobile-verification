<?php

namespace Fouladgar\MobileVerifier\Concerns;

use Fouladgar\MobileVerifier\Events\Verified;
use Fouladgar\MobileVerifier\Exceptions\InvalidTokenException;
use Fouladgar\MobileVerifier\Http\Requests\VerificationRequest;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait VerifiesMobiles
{
    use RedirectsUsers;

    /**
     * {@inheritdoc}
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
                : back()->withErrors(['token' => $e->getMessage()]);
        }

        event(new Verified($user, $request->all()));

        return $request->expectsJson()
            ? $this->successMessage()
            : redirect($this->redirectPath())
                ->with('mobileVerificationVerified', __('mobile_verifier.successful_verification'));
    }

    /**
     * {@inheritdoc}
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson() ? $this->unprocessableEntity() : redirect($this->redirectPath());
        }

        $this->tokenBroker->sendToken($user);

        return $request->expectsJson()
            ? $this->successMessage()
            : back()->with('mobileVerificationResend', __('mobile_verifier.successful_resend'));
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
