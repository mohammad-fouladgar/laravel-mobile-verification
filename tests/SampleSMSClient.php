<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\Payload;
use Fouladgar\MobileVerifier\Contracts\SmsClient;

class SampleSMSClient implements SmsClient
{
    public function sendMessage(Payload $payload)
    {
//        return $this->send($payload->getTo(), $payload->getToken());
        var_dump($payload);
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
