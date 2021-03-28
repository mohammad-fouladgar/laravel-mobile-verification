<?php

namespace Fouladgar\MobileVerification\Exceptions;

use Exception;

class SMSClientNotFoundException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('SMS client is not specified in the config file.');
    }
}
