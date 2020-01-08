<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\Payload;
use Fouladgar\MobileVerifier\Contracts\SmsClient;

class KavenegarClient implements SmsClient
{
    public function sendMessage(Payload $payload)
    {
//        return $this->send($payload['to'], $payload['token']);
        dd($payload);
    }

    /**
     * @param $number
     * @param $message
     * @return mixed
     */
    public function send($number, $message)
    {
        try {
            return Kavenegar::Send(config('kavenegar.sender'), $number, $message);
        } catch (\Exception $e) {
            //todo log error
        }
    }
}
