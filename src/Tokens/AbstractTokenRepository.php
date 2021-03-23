<?php

namespace Fouladgar\MobileVerification\Tokens;

use Exception;
use Illuminate\Support\Carbon;

abstract class AbstractTokenRepository implements TokenRepositoryInterface
{
    /**
     * The number of seconds a token should last.
     *
     * @var int
     */
    protected $expires;

    /**
     * @var int
     */
    protected $tokenLength;

    /**
     * Create a new token repository instance.
     *
     * @param  ConnectionInterface  $connection
     * @param  string  $table
     * @param  int  $expires
     * @param  int  $tokenLength
     */
    public function __construct($expires, $tokenLength)
    {
        $this->expires = $expires;
        $this->tokenLength = $tokenLength;
    }

    /**
     * Set Expires token.
     *
     * @param  int  $expires
     *
     * @return $this
     */
    public function setExpires(int $expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Create a new token for the user.
     *
     * @throws Exception
     *
     * @return string
     */
    protected function createNewToken(): string
    {
        $tokenLength = config('mobile_verifier.token_length', 5);

        return (string)random_int(10 ** ($tokenLength - 1), (10 ** $tokenLength) - 1);
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string  $expiresAt
     *
     * @return bool
     */
    protected function tokenExpired($expiresAt): bool
    {
        return Carbon::parse($expiresAt)->addMinutes($this->expires)->isPast();
    }

    /**
     * Build the record payload for the table.
     *
     * @param $mobile
     * @param  string  $token
     *
     * @throws Exception
     *
     * @return array
     */
    protected function getPayload($mobile, $token): array
    {
        return ['mobile' => $mobile, 'token' => $token];
    }

    /**
     * Insert into storage token driver.
     *
     * @param $mobile
     * @param $token
     *
     * @return bool
     */
    abstract protected function insertIntoStorageDriver($mobile, $token): bool;
}
