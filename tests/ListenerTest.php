<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Listeners\SendMobileVerificationNotification;
use Fouladgar\MobileVerification\Tests\Models\User;
use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Illuminate\Auth\Events\Registered;
use Mockery as m;

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

    public function setUp(): void
    {
        parent::setUp();

        $this->tokenBroker = m::mock(TokenBrokerInterface::class);
        $this->verifiableUser = m::mock(VerifiableUser::class)->makePartial();
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_not_listen_when_user_is_not_verifiable(): void
    {
        $user = m::mock(User::class)->makePartial();

        $this->tokenBroker->shouldNotReceive('sendToken');

        $listener = new SendMobileVerificationNotification($this->tokenBroker);

        $listener->handle(new Registered($user));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_not_listen_when_user_is_already_verified(): void
    {
        $this->verifiableUser->shouldReceive('hasVerifiedMobile')->andReturn(true);
        $this->tokenBroker->shouldNotReceive('sendToken');

        $listener = new SendMobileVerificationNotification($this->tokenBroker);

        $listener->handle(new Registered($this->verifiableUser));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_listen_successfully(): void
    {
        $this->verifiableUser->shouldReceive('hasVerifiedMobile')->andReturn(false);
        $this->tokenBroker->shouldReceive('sendToken');

        $listener = new SendMobileVerificationNotification($this->tokenBroker);

        $listener->handle(new Registered($this->verifiableUser));
    }
}
