<?php

namespace Fouladgar\MobileVerifier\Repository;

use Exception;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;

class DatabaseTokenRepository implements TokenRepositoryInterface
{
    /**
     * @param MustVerifyMobile $user
     * @return string
     * @throws Exception
     */
    public function create(MustVerifyMobile $user): string
    {
        $tokenCount = config('mobile_verifier.token_count');

        return (string)random_int(10 ** ($tokenCount - 1), (10 ** $tokenCount) - 1);
    }

    /**
     * @inheritDoc
     */
    public function exists($user, $token)
    {
        // TODO: Implement exists() method.
    }

    /**
     * @inheritDoc
     */
    public function delete($user)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function deleteExpired()
    {
        // TODO: Implement deleteExpired() method.
    }
}
