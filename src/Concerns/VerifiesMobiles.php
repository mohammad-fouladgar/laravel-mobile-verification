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
     * Show the mobile verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function show(Request $request)
    // {
    //     return $request->user()->hasVerifiedMobile()
    //                     ? redirect($this->redirectPath())
    //                     : view('auth.mobile_verify');
    // }


     /**
     * @param VerificationRequest $request
     * @return JsonResponse|\Illuminate\Http\Response
     * @throws Throwable
     */
    public function verify(VerificationRequest $request)
    {
        $user  = $request->user();

        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson()
                        ? response()->json(['message' => 'Your mobile already has been verified.'], 422)
                        : 'assert redirect.'; 
        }

        try {
            $this->tokenBroker->verifyToken($user, $request->token);
        } catch (InvalidTokenException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        event(new Verified($user, $request->all()));

        return $request->expectsJson()
                    ? response()->json(['message' => 'Your mobile has been verified successfully.'], 200)
                    : 'show a success message in a view form'; 
                    // redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * @param Request $request
     * @return JsonResponse|view|redireccccccccccccct
     * @throws Exception
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedMobile()) {
            return $request->expectsJson()
                        ? response()->json(['message' => 'Your mobile already has been verified.'], 422)
                        : 'assert redirect.'; 
        }

        $this->tokenBroker->sendToken($user);

        return $request->expectsJson()
                    ? response()->json(['message' => 'The Token has been resend successfully.'], 200)
                    : 'show a success message in a view form'; 
                    // redirect($this->redirectPath())->with('verified', true);
    }

   
}
