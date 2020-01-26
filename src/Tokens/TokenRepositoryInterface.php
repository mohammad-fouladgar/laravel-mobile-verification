<?php

namespace Fouladgar\MobileVerification\Tokens;

use Exception;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile;

interface TokenRepositoryInterface
{
    /**
     * Create a new token record.
     *
     * @param MustVerifyMobile $user
     *
     * @throws Exception
     *
     * @return string
     */
    public function create(MustVerifyMobile $user): string;

    /**
     * Determine if a token record exists and is valid.
     *
     * @param $user
     * @param $token
     *
     * @return bool
     */
    public function exists($user, $token): bool;

    /**
     * Delete all existing tokens from the database.
     *
     * @param MustVerifyMobile $user
     *
     * @return int|null
     */
    public function deleteExisting(MustVerifyMobile $user): ?int;
}
