<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\TokenBroker;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;
use Fouladgar\MobileVerifier\Exceptions\InvalidTokenException;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Mockery as m;

class TokenBrokerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->user = m::mock(VerifiableUser::class)->makePartial();
        $this->tokenRepository = m::mock(TokenRepositoryInterface::class);
    }
    /** @test */
    public function it_can_send_token_to_a_verifiable_user()
    {
        $this->user->shouldReceive('sendMobileVerifierNotification');
        $this->tokenRepository->shouldReceive('create')->andReturn('token_123');

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $tokenBroker->sendToken($this->user);
    }

    /** @test */
    public function it_can_verify_token1()
    {
        $this->tokenRepository->shouldReceive('exists')->andReturn(false);

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $this->expectException(InvalidTokenException::class);

        $tokenBroker->verifyToken($this->user,'token_123');
    }

    /** @test */
    public function it_can_verify_successfuly()
    {
        $this->user->shouldReceive('markMobileAsVerified')->andReturn(true);
        $this->tokenRepository->shouldReceive('exists')->andReturn(true);
        $this->tokenRepository->shouldReceive('deleteExisting');

        $tokenBroker = new TokenBroker($this->tokenRepository);

        $this->assertTrue($tokenBroker->verifyToken($this->user,'token_123'));
    }
}
