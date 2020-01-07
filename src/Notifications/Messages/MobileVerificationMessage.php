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
     * Create a new message instance.
     *
     * @param string $content
     */
    // public function __construct($code = '', $template_id='4303')
    // {
    //     $this->code        = $code;
    //     $this->template_id = $template_id;
    // }

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

    /**
     * Set the message template ID.
     *
     * @param string $template_id
     *
     * @return $this
     */
    // public function templateId($template_id)
    // {
    //     $this->template_id = $template_id;

    //     return $this;
    // }
}
