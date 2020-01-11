<?php

namespace Fouladgar\MobileVerifier\Notifications\Messages;

use Fouladgar\MobileVerifier\Concerns\Payload;

class MobileVerificationMessage
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $to;

    /**
     * @param $to
     * @return MobileVerificationMessage
     */
    public function to($to): MobileVerificationMessage
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param $token
     * @return MobileVerificationMessage
     */
    public function token($token): MobileVerificationMessage
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Payload
     */
    public function getPayload(): Payload
    {
        return (new Payload())->setTo($this->to)
                              ->setToken($this->token);
    }
}