<?php

declare(strict_types=1);

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
    public function it_can_successfully_send_verification_token(): void
    {
        $notification = new VerifyMobile('token_123');
        $notifiable = new VerifiableUser();

        $notifiable->mobile = '555555';

        $client = m::mock(SMSClient::class);
        $verificationChannel = new VerificationChannel($client);

        $client->shouldReceive('sendMessage')->andReturn(true);

        $this->assertTrue($verificationChannel->send($notifiable, $notification));
    }

    /** @test */
    public function it_not_working_on_not_verifiable_user_model(): void
    {
        $notification = new VerifyMobile('token_123');
        $notifiable = new User();

        $client = m::mock(SMSClient::class);
        $verificationChannel = new VerificationChannel($client);

        $client->shouldNotReceive('sendMessage');

        $this->assertNull($verificationChannel->send($notifiable, $notification));
    }
}
