<?php

namespace Fouladgar\MobileVerification\Tokens;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

class DatabaseTokenRepository extends AbstractTokenRepository
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
     * Create a new token repository instance.
     *
     * @param  ConnectionInterface  $connection
     * @param  string  $table
     * @param  int  $expires
     * @param  int  $tokenLength
     */
    public function __construct(
        $expires,
        $tokenLength,
        $table,
        ConnectionInterface $connection
    ) {
        parent::__construct($expires, $tokenLength);
        $this->table = $table;
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function create(MustVerifyMobile $user): string
    {
        $mobile = $user->getMobileForVerification();

        $this->deleteExisting($user);

        $token = $this->createNewToken();

        $this->insertIntoStorageDriver($mobile, $token);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExisting(MustVerifyMobile $user): ?int
    {
        return optional($this->getTable()->where('mobile', $user->getMobileForVerification()))->delete();
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

    protected function insertIntoStorageDriver($mobile, $token): bool
    {
        return $this->getTable()->insert($this->getPayload($mobile, $token));
    }

    /**
     * @inheritDoc
     */
    protected function getPayload($mobile, $token): array
    {
        return parent::getPayload($mobile, $token) + ['expires_at' => now()->addMinutes($this->expires)];
    }

    /**
     * {@inheritdoc}
     */
    public function exists($user, $token): bool
    {
        /** @var MustVerifyMobile $user */
        $record = (array)$this->getTable()
            ->where($user->getMobileField(), $user->getMobileForVerification())
            ->where('token', $token)
            ->first();

        return $record && ! $this->tokenExpired($record['expires_at']);
    }
}
