<?php

namespace Fouladgar\MobileVerifier\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct('The token has been expired or invalid.');
    }
}
