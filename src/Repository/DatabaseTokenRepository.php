<?php

namespace Fouladgar\MobileVerifier\Repository;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Exception;

class DatabaseTokenRepository implements TokenRepositoryInterface
{
    /**
     * The database connection instance.
     *
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * The token database table.
     *
     * @var string
     */
    protected $table;

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
     * @param ConnectionInterface $connection
     * @param string $table
     * @param int $expires
     * @param int $tokenLength
     */
    public function __construct(
        ConnectionInterface $connection,
        $table = 'mobile_verification_tokens',
        $expires = 5,
        $tokenLength = 5
    ) {
        $this->table       = $table;
        $this->expires     = $expires * 60;
        $this->connection  = $connection;
        $this->tokenLength = $tokenLength;
    }

    /**
     * {@inheritDoc}
     */
    public function create(MustVerifyMobile $user): string
    {
        $mobile = $user->getMobileForVerification();

        $this->deleteExisting($user);

        $token = $this->createNewToken();

        $this->getTable()->insert($this->getPayload($mobile, $token));

        return $token;
    }

    /**
     * Begin a new database query against the table.
     *
     * @return Builder
     */
    protected function getTable(): Builder
    {
        return $this->connection->table($this->table);
    }

    /**
     * Delete all existing tokens from the database.
     *
     * @param MustVerifyMobile $user
     * @return int
     */
    protected function deleteExisting(MustVerifyMobile $user): int
    {
        return $this->getTable()->where('mobile', $user->getMobileForVerification())->delete();
    }

    /**
     * Build the record payload for the table.
     *
     * @param $mobile
     * @param string $token
     * @return array
     * @throws Exception
     */
    protected function getPayload($mobile, $token): array
    {
        return ['mobile' => $mobile, 'token' => $token, 'expires_at' => now()->addMinutes($this->expires)];
    }

    /**
     * Create a new token for the user.
     *
     * @return string
     * @throws Exception
     */
    protected function createNewToken(): string
    {
        $tokenLength = config('mobile_verifier.token_length');

        return (string)random_int(10 ** ($tokenLength - 1), (10 ** $tokenLength) - 1);
    }
}
