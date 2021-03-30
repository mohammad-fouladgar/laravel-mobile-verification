<?php

namespace Fouladgar\MobileVerification\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct(__('MobileVerification::mobile_verifier.expired_or_invalid'), 406);
    }
}
