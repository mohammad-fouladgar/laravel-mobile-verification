<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\TokenBroker;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Mockery as m;

class TokenBrokerTest extends TestCase
{
    /** @test */
    public function it_can1()
    {
        $tokenRepository = m::mock(TokenRepositoryInterface::class);
        $verifiableUser = m::mock(VerifiableUser::class)->makePartial();

        $verifiableUser->shouldReceive('sendMobileVerifierNotification');
        $tokenRepository->shouldReceive('create')->andReturn('token_123');

        $tokenBroker = new TokenBroker($tokenRepository);

        $tokenBroker->sendToken($verifiableUser);
    }
}
