<?php

namespace Fouladgar\MobileVerifier\Contracts;

use Exception;

interface TokenRepositoryInterface
{
    /**
     * Create a new token record.
     *
     * @param MustVerifyMobile $user
     * @return string
     * @throws Exception
     */
    public function create(MustVerifyMobile $user): string;
}
