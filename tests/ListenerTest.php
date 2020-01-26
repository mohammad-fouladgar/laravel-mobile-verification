<?php

namespace Fouladgar\MobileVerifier\Tests;

use Mockery as m;
use Illuminate\Auth\Events\Registered;
use Fouladgar\MobileVerifier\Tests\Models\User;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerifier\Tokens\TokenBrokerInterface;
use Fouladgar\MobileVerifier\Listeners\SendMobileVerificationNotification;

class ListenerTest extends TestCase
{
    /**
     * @var TokenBrokerInterface|m\LegacyMockInterface|m\MockInterface
     */
    private $tokenBroker;

    /**
     * @var m\Mock
     */
    private $verifiableUser;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tokenBroker    = m::mock(TokenBrokerInterface::class);
        $this->verifiableUser = m::mock(VerifiableUser::class)->makePartial();
    }

    /** @test */
    public function it_can_not_listen_when_user_is_not_verifiable()
    {
        $user = m::mock(User::class)->makePartial();

        $this->tokenBroker->shouldNotReceive('sendToken');

        $listener = new SendMobileVerificationNotification($this->tokenBroker);

        $listener->handle(new Registered($user));
    }

    /** @test */
    public function it_can_not_listen_when_user_is_already_verified()
    {
        $this->verifiableUser->shouldReceive('hasVerifiedMobile')->andReturn(true);
        $this->tokenBroker->shouldNotReceive('sendToken');

        $listener = new SendMobileVerificationNotification($this->tokenBroker);

        $listener->handle(new Registered($this->verifiableUser));
    }

    /** @test */
    public function it_can_listen_successfully()
    {
        $this->verifiableUser->shouldReceive('hasVerifiedMobile')->andReturn(false);
        $this->tokenBroker->shouldReceive('sendToken');

        $listener = new SendMobileVerificationNotification($this->tokenBroker);

        $listener->handle(new Registered($this->verifiableUser));
    }
}
