<?php

namespace Fouladgar\MobileVerifier\Notifications\Messages;

class MobileVerificationMessage
{
    /**
     * The message code.
     *
     * @var string
     */
    private $code;

    /**
     * Set the message code.
     *
     * @param string $code
     *
     * @return $this
     */
    public function code($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }
}
