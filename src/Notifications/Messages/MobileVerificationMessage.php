<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Notifications\Messages;

class MobileVerificationMessage
{
    /** @var string  */
    protected $token;

    /** @var string  */
    protected $to;

    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function token(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPayload(): Payload
    {
        return (new Payload())->setTo($this->to)->setToken($this->token);
    }
}
