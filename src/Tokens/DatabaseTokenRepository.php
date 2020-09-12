<?php

namespace Fouladgar\MobileVerification\Tokens;

use Exception;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

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
     * @param string              $table
     * @param int                 $expires
     * @param int                 $tokenLength
     */
    public function __construct(
        ConnectionInterface $connection,
        $table = 'mobile_verification_tokens',
        $expires = 5,
        $tokenLength = 5
    ) {
        $this->table = $table;
        $this->expires = $expires;
        $this->connection = $connection;
        $this->tokenLength = $tokenLength;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function deleteExisting(MustVerifyMobile $user): ?int
    {
        return optional($this->getTable()->where('mobile', $user->getMobileForVerification()))->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function exists($user, $token): bool
    {
        /** @var MustVerifyMobile $user */
        $record = (array) $this->getTable()
            ->where('mobile', $user->getMobileForVerification())
            ->where('token', $token)
            ->first();

        return $record && !$this->tokenExpired($record['expires_at']);
    }

    /**
     * Build the record payload for the table.
     *
     * @param $mobile
     * @param string $token
     *
     * @throws Exception
     *
     * @return array
     */
    protected function getPayload($mobile, $token): array
    {
        return ['mobile' => $mobile, 'token' => $token, 'expires_at' => now()->addMinutes($this->expires)];
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

        return (string) random_int(10 ** ($tokenLength - 1), (10 ** $tokenLength) - 1);
    }

    /**
     * Determine if the token has expired.
     *
     * @param string $expiresAt
     *
     * @return bool
     */
    protected function tokenExpired($expiresAt): bool
    {
        return Carbon::parse($expiresAt)->addMinutes($this->expires)->isPast();
    }
}
