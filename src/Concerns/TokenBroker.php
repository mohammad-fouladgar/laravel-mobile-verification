<?php

namespace Fouladgar\MobileVerifier\Concerns;

use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerifier\Exceptions\InvalidTokenException;

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
        $user->sendMobileVerifierNotification(
            $this->tokenRepository->create($user)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function verifyToken(MustVerifyMobile $user, $token): bool
    {
        throw_unless($this->tokenExists($user, $token), InvalidTokenException::class);

        $user->markMobileAsVerified();

        $this->tokenRepository->deleteExisting($user);

        return true;
    }

    /**
     * @param MustVerifyMobile $user
     * @param $token
     * @return bool
     */
    protected function tokenExists(MustVerifyMobile $user, $token): bool
    {
        return $this->tokenRepository->exists($user, $token);
    }
}
