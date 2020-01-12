<?php

namespace Fouladgar\MobileVerifier\Http\Controllers;

use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Fouladgar\MobileVerifier\Exceptions\InvalidTokenException;
use Fouladgar\MobileVerifier\Http\Requests\VerificationRequest;
use Fouladgar\MobileVerifier\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Throwable;
use Exception;

class MobileVerificationController extends Controller
{
    /**
     * @var TokenBrokerInterface
     */
    protected $tokenBroker;

    /**
     * ApproveVerificationController constructor.
     * @param TokenBrokerInterface $tokenBroker
     */
    public function __construct(TokenBrokerInterface $tokenBroker)
    {
        $this->middleware('auth');

        $this->tokenBroker = $tokenBroker;
    }

    /**
     * @param VerificationRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function verify(VerificationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user  = $request->user();
        $token = $validated['token'];

        try {
            $this->tokenBroker->verifyToken($user, $token);
        } catch (InvalidTokenException $e) {
            return response()->json(['message' => $e->getMessage()], 406);
        }

        event(new Verified($user, $request->all()));

        return response()->json(['message' => 'Your mobile has been verified successfully.'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|null
     * @throws Exception
     */
    public function resend(Request $request): ?JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedMobile()) {
            return response()->json(['message' => 'Your mobile already has been verified.'], 422);
        }

        $this->tokenBroker->sendToken($user);

        return response()->json(['message' => 'The Token has been resend successfully.'], 200);
    }
}
