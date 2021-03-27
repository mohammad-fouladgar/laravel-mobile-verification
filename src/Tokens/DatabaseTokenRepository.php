<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

class DatabaseTokenRepository extends AbstractTokenRepository
{
    /**
     * The database connection instance.
     */
    protected ConnectionInterface $connection;

    /**
     * The token database table.
     */
    protected string $table;

    public function __construct(
        int $expires,
        int $tokenLength,
        string $table,
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
    public function deleteExisting(MustVerifyMobile $user): void
    {
        optional($this->getTable()->where('mobile', $user->getMobileForVerification()))->delete();
    }

    /**
     * Begin a new database query against the table.
     */
    protected function getTable(): Builder
    {
        return $this->connection->table($this->table);
    }

    /**
     * @throws \Exception
     */
    protected function insertIntoStorageDriver(string $mobile, string $token): bool
    {
        return $this->getTable()->insert($this->getPayload($mobile, $token));
    }

    /**
     * @inheritDoc
     */
    protected function getPayload(string $mobile, string $token): array
    {
        return parent::getPayload($mobile, $token) + ['expires_at' => now()->addMinutes($this->expires)];
    }

    /**
     * {@inheritdoc}
     */
    public function exists(MustVerifyMobile $user, string $token): bool
    {
        $record = (array) $this->getTable()
            ->where($user->getMobileField(), $user->getMobileForVerification())
            ->where('token', $token)
            ->first();

        return $record && ! $this->tokenExpired($record['expires_at']);
    }
}
