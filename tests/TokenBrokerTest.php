<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Exceptions\InvalidTokenException;
use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerification\Tokens\TokenBroker;
use Fouladgar\MobileVerification\Tokens\TokenRepositoryInterface;
use Mockery as m;

class TokenBrokerTest extends TestCase
{
    /**
     * @var m\Mock
     */
    private $user;

    /**
     * @var TokenRepositoryInterface|m\LegacyMockInterface|m\MockInterface
     */
    private $tokenRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = m::mock(VerifiableUser::class)->makePartial();
        $this->tokenRepository = m::mock(TokenRepositoryInterface::class);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_send_token_to_a_verifiable_user(): void
    {
        $this->user->shouldReceive('sendMobileVerifierNotification');
        $this->tokenRepository->shouldReceive('create')->andReturn('token_123');

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $tokenBroker->sendToken($this->user);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function it_fails_on_invalid_token_when_verifying(): void
    {
        $this->tokenRepository->shouldReceive('exists')->andReturn(false);

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $this->expectException(InvalidTokenException::class);

        $tokenBroker->verifyToken($this->user, 'token_123');
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function it_can_verify_user_successfully(): void
    {
        $this->user->shouldReceive('markMobileAsVerified')->andReturn(true);
        $this->tokenRepository->shouldReceive('exists')->andReturn(true);
        $this->tokenRepository->shouldReceive('deleteExisting');

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $this->assertTrue($tokenBroker->verifyToken($this->user, 'token_123'));
    }
}
