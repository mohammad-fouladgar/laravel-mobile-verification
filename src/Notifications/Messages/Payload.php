<?php

namespace Fouladgar\MobileVerification\Notifications\Messages;

class Payload
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $to;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return Payload
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @return Payload
     */
    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }
}
