<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Illuminate\Support\Carbon;

abstract class AbstractTokenRepository implements TokenRepositoryInterface
{
    /**
     * The number of seconds a token should last.
     */
    protected int $expires;

    protected int $tokenLength;

    public function __construct(int $expires, int $tokenLength)
    {
        $this->expires     = $expires;
        $this->tokenLength = $tokenLength;
    }

    /**
     * Set Expires token.
     */
    public function setExpires(int $expires): self
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Create a new token for the user.
     *
     * @throws \Exception
     */
    protected function createNewToken(): string
    {
        $tokenLength = config('mobile_verifier.token_length', 5);

        return (string) random_int(10 ** ($tokenLength - 1), (10 ** $tokenLength) - 1);
    }

    /**
     * Determine if the token has expired.
     */
    protected function tokenExpired(string $expiresAt): bool
    {
        return Carbon::parse($expiresAt)->addMinutes($this->expires)->isPast();
    }

    /**
     * Build the record payload for the table.
     *
     * @throws \Exception
     */
    protected function getPayload(string $mobile, string $token): array
    {
        return ['mobile' => $mobile, 'token' => $token, 'sent_at' => now()->toDateTimeString()];
    }

    /**
     * Insert into token storage.
     */
    abstract protected function insert(string $mobile, string $token): bool;
}
