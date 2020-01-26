<?php

namespace Fouladgar\MobileVerification\Tokens;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Exceptions\InvalidTokenException;

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
     * {@inheritdoc}
     */
    public function sendToken(MustVerifyMobile $user): void
    {
        $user->sendMobileVerifierNotification(
            $this->tokenRepository->create($user)
        );
    }

    /**
     * {@inheritdoc}
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
     *
     * @return bool
     */
    protected function tokenExists(MustVerifyMobile $user, $token): bool
    {
        return $this->tokenRepository->exists($user, $token);
    }
}
