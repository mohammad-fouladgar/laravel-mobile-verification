<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Exception;
use Illuminate\Support\Carbon;

abstract class AbstractTokenRepository implements TokenRepositoryInterface
{
    public function __construct(protected int $expires, protected int $tokenLength)
    {
    }

    public function setExpires(int $expires): self
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function createNewToken(): string
    {
        $tokenLength = config('mobile_verifier.token_length', 5);

        return (string) random_int(10 ** ($tokenLength - 1), (10 ** $tokenLength) - 1);
    }

    protected function tokenExpired(string $expiresAt): bool
    {
        return Carbon::parse($expiresAt)->isPast();
    }

    /**
     * Build the record payload for the table.
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
