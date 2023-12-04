<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Exception;
use Fouladgar\MobileVerification\Listeners\SendMobileVerificationNotification;
use Fouladgar\MobileVerification\Tests\Models\User;
use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Mockery as m;

class ListenerTest extends TestCase
{
    private TokenBrokerInterface|m\LegacyMockInterface|m\MockInterface $tokenBroker;

    /**
     * @var m\Mock
     */
    private $verifiableUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->tokenBroker = m::mock(TokenBrokerInterface::class);
        $this->verifiableUser = m::mock(VerifiableUser::class);
    }

    /**
     * @test
     *
     * @throws Exception
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
     * @throws Exception
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
     * @throws Exception
     */
    public function it_can_listen_successfully(): void
    {
        $this->verifiableUser->shouldReceive('hasVerifiedMobile')->andReturn(false);
        $this->tokenBroker->shouldReceive('sendToken');

        $listener = new SendMobileVerificationNotification($this->tokenBroker);

        $listener->handle(new Registered($this->verifiableUser));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function it_should_not_queue(): void
    {
        Event::fake();

        Event::assertListening(
            Registered::class,
            SendMobileVerificationNotification::class
        );
    }
}
