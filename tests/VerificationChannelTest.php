<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Contracts\SmsClient;
use Fouladgar\MobileVerifier\Notifications\Channels\VerificationChannel;
use Fouladgar\MobileVerifier\Notifications\VerifyMobile;
use Fouladgar\MobileVerifier\Tests\Models\User;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Mockery as m;

class VerificationChannelTest extends TestCase
{
    /** @test */
    public function it_can_successfully_send_verification_token()
    {
        $notification = new VerifyMobile('token_123');
        $notifiable = new VerifiableUser();

        $notifiable->mobile = '555555';

        $verificationChannel = new VerificationChannel(
            $client = m::mock(SmsClient::class)
        );

        $client->shouldReceive('sendMessage')->andReturn(true);

        $this->assertTrue($verificationChannel->send($notifiable, $notification));
    }

    /** @test */
    public function it_not_working_on_not_vefifable_user_model()
    {
        $notification = new VerifyMobile('token_123');
        $notifiable = new User();

        $verificationChannel = new VerificationChannel(
            $client = m::mock(SmsClient::class)
        );

        $client->shouldNotReceive('sendMessage');

        $this->assertNull($verificationChannel->send($notifiable, $notification));
    }
}
