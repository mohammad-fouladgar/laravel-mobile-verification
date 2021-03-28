<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Notifications\Messages;

class Payload
{
    private string $token;

    private string $to;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }
}
