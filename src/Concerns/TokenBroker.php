<?php

namespace Fouladgar\MobileVerifier\Concerns;

use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;

class TokenBroker implements TokenBrokerInterface
{
    /**
     * @var TokenRepositoryInterface
     */
    protected $tokenRepository;

    /**
     * Create a new token broker instance.
     *
     * @param TokenRepositoryInterface $tokenRepository
     */
    public function __construct(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function sendToken(MustVerifyMobile $user): void
    {
        $user->sendMobileVerifierNotification($this->tokenRepository->create($user));
    }
}
