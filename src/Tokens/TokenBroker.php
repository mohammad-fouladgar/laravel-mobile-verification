<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerification\Exceptions\InvalidTokenException;

class TokenBroker implements TokenBrokerInterface
{

    public function __construct(protected TokenRepositoryInterface $tokenRepository)
    {
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

    public function tokenExists(MustVerifyMobile $user, string $token): bool
    {
        return $this->tokenRepository->exists($user, $token);
    }

    public function getLatestSentAt(MustVerifyMobile $user, string $token): string
    {
        return $this->tokenRepository->latestSentAt($user, $token);
    }
}
