<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Exceptions\InvalidTokenException;

class TokenBroker implements TokenBrokerInterface
{
    protected TokenRepositoryInterface $tokenRepository;

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
    public function verifyToken(MustVerifyMobile $user, string $token): bool
    {
        throw_unless($this->tokenExists($user, $token), InvalidTokenException::class);

        $user->markMobileAsVerified();

        $this->tokenRepository->deleteExisting($user);

        return true;
    }

    protected function tokenExists(MustVerifyMobile $user, string $token): bool
    {
        return $this->tokenRepository->exists($user, $token);
    }
}
