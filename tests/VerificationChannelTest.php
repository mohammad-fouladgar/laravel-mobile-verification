<?php

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Contracts\SMSClient;
use Fouladgar\MobileVerification\Notifications\Channels\VerificationChannel;
use Fouladgar\MobileVerification\Notifications\VerifyMobile;
use Fouladgar\MobileVerification\Tests\Models\User;
use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
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
            $client = m::mock(SMSClient::class)
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
            $client = m::mock(SMSClient::class)
        );

        $client->shouldNotReceive('sendMessage');

        $this->assertNull($verificationChannel->send($notifiable, $notification));
    }
}
