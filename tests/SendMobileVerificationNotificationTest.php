<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Fouladgar\MobileVerifier\Listeners\SendMobileVerificationNotification;
use Fouladgar\MobileVerifier\Tests\Models\User;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Illuminate\Auth\Events\Registered;
use Mockery as m;

class SendMobileVerificationNotificationTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->tokenBroker = m::mock(TokenBrokerInterface::class);
        $this->verifiableUser = m::mock(VerifiableUser::class)->makePartial();
    }
   /** @test */
   public function it_can1()
   {
        $tokenBroker = $this->tokenBroker;
        $user        = m::mock(User::class)->makePartial();

        $tokenBroker->shouldNotReceive('sendToken');

        $listener = new SendMobileVerificationNotification($tokenBroker);

        $listener->handle(new Registered($user));
   }

   /** @test */
   public function it_can2()
   {
        $tokenBroker    = $this->tokenBroker;
        $verifiableUser = $this->verifiableUser;

        $verifiableUser->shouldReceive('hasVerifiedMobile')->andReturn(true);
        $tokenBroker->shouldNotReceive('sendToken');

        $listener = new SendMobileVerificationNotification($tokenBroker);

        $listener->handle(new Registered($verifiableUser));
   }

   /** @test */
   public function it_can3()
   {
        $tokenBroker    = $this->tokenBroker;
        $verifiableUser = $this->verifiableUser;

        $verifiableUser->shouldReceive('hasVerifiedMobile')->andReturn(false);
        $tokenBroker->shouldReceive('sendToken');

        $listener = new SendMobileVerificationNotification($tokenBroker);

        $listener->handle(new Registered($verifiableUser));
   }
}
