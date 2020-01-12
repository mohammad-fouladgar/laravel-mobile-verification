<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\TokenBroker;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;
use Fouladgar\MobileVerifier\Exceptions\InvalidTokenException;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Mockery as m;
use Throwable;
use Exception;

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

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user            = m::mock(VerifiableUser::class)->makePartial();
        $this->tokenRepository = m::mock(TokenRepositoryInterface::class);
    }

    /** @test
     * @throws Exception
     */
    public function it_can_send_token_to_a_verifiable_user()
    {
        $this->user->shouldReceive('sendMobileVerifierNotification');
        $this->tokenRepository->shouldReceive('create')->andReturn('token_123');

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $tokenBroker->sendToken($this->user);
    }

    /** @test
     * @throws Throwable
     */
    public function it_fails_on_invalid_token_when_verifing()
    {
        $this->tokenRepository->shouldReceive('exists')->andReturn(false);

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $this->expectException(InvalidTokenException::class);

        $tokenBroker->verifyToken($this->user, 'token_123');
    }

    /** @test
     * @throws Throwable
     */
    public function it_can_verify_user_successfuly()
    {
        $this->user->shouldReceive('markMobileAsVerified')->andReturn(true);
        $this->tokenRepository->shouldReceive('exists')->andReturn(true);
        $this->tokenRepository->shouldReceive('deleteExisting');

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $this->assertTrue($tokenBroker->verifyToken($this->user, 'token_123'));
    }
}
