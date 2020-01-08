<?php

namespace Fouladgar\MobileVerifier\Contracts;

interface TokenRepositoryInterface
{
    /**
     * @param MustVerifyMobile $user
     * @return string
     */
    public function create(MustVerifyMobile $user): string;

    /**
     * Determine if a token record exists and is valid.
     *
     * @param string $token
     * @return bool
     */
    public function exists($user, $token);

    /**
     * Delete a token record.
     *
     * @return void
     */
    public function delete($user);

    /**
     * Delete expired tokens.
     *
     * @return void
     */
    public function deleteExpired();
}
