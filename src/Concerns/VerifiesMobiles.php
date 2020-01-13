<?php

namespace Fouladgar\MobileVerifier\Concerns;

use Fouladgar\MobileVerifier\Exceptions\InvalidTokenException;
use Fouladgar\MobileVerifier\Http\Requests\VerificationRequest;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Fouladgar\MobileVerifier\Events\Verified;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\View\View;

trait VerifiesMobiles
{
    use RedirectsUsers;

    /**
     * @param VerificationRequest $request
     * @return Factory|JsonResponse|Redirector
     */
    public function verify(VerificationRequest $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson() ? $this->unprocessableEntityJson() : redirect($this->redirectPath());
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
     * @return Factory|JsonResponse|Redirector
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson() ? $this->unprocessableEntityJson() : $this->unprocessableEntityView();
        }

        $this->tokenBroker->sendToken($user);

        return $request->expectsJson() ? $this->successMessage() :
            redirect($this->redirectPath())->with('mobileVerificationResend', true);
    }

    /**
     * @return JsonResponse
     */
    protected function successMessage(): JsonResponse
    {
        return response()->json(['message' => 'Your mobile has been verified successfully.'], 200);
    }

    /**
     * @return JsonResponse
     */
    protected function unprocessableEntityJson(): JsonResponse
    {
        return response()->json(['message' => 'Your mobile already has been verified.'], 422);
    }

    /**
     * @return Factory|View
     */
    protected function unprocessableEntityView()
    {
        return view('MobileVerifier::auth.mobile_verify')
            ->with('mobileVerificationError', 'Your mobile already has been verified.');
    }
}
