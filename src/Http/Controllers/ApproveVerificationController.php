<?php

use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Renter\Payment\Http\Requests\VerificationRequest;
use Fouladgar\MobileVerifier\Events\Verified;

class ApproveVerificationController
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
        $this->tokenBroker = $tokenBroker;
    }

    /**
     * @param VerificationRequest $request
     * @throws Throwable
     */
    public function __invoke(VerificationRequest $request)
    {
        $validated = $request->validated();

        $user  = $request->user();
        $token = $validated['token'];

        $this->tokenBroker->verifyToken($user, $token);

        event(new Verified($user, $request->all()));
    }
}
