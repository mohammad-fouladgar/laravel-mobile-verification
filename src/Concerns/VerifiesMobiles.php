<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Concerns;

use Fouladgar\MobileVerification\Events\Verified;
use Fouladgar\MobileVerification\Exceptions\InvalidTokenException;
use Fouladgar\MobileVerification\Http\Requests\VerificationRequest;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

trait VerifiesMobiles
{
    use RedirectsUsers;
    use Responses;

    public function verify(VerificationRequest $request): ViewFactory|JsonResponse|RedirectResponse
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
            : redirect($this->redirectPath())->with(
                'mobileVerificationVerified',
                __('MobileVerification::mobile_verifier.successful_verification')
            );
    }

    public function resend(Request $request): ViewFactory|JsonResponse|Redirector|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson() ? $this->unprocessableEntity() : redirect($this->redirectPath());
        }

        $this->tokenBroker->sendToken($user);

        return $request->expectsJson()
            ? $this->successResendMessage()
            : back()->with('mobileVerificationResend', __('MobileVerification::mobile_verifier.successful_resend'));
    }
}
