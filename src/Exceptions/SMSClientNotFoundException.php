<?php

namespace Fouladgar\MobileVerifier\Exceptions;

use Exception;

class SMSClientNotFoundException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('SMS client is not specified in config file.');
    }
}
