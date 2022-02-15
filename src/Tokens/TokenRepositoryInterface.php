<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tokens;

use Exception;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;

interface TokenRepositoryInterface
{
    /**
     * Create a new token record.
     *
     * @throws Exception
     */
    public function create(MustVerifyMobile $user): string;

    /**
     * Determine if a token record exists and is valid.
     */
    public function exists(MustVerifyMobile $user, string $token): bool;

    /**
     * Delete all existing tokens from the database.
     */
    public function deleteExisting(MustVerifyMobile $user): void;

    public function latestSentAt(MustVerifyMobile $user, string $token): string;
}
