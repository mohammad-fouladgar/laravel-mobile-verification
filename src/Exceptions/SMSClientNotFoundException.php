<?php

namespace Fouladgar\MobileVerification\Exceptions;

use Exception;

class SMSClientNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('SMS client is not specified in the config file.');
    }
}
