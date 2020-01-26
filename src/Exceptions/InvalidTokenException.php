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
        parent::__construct('The token has been expired or invalid.', 406);
    }
}
