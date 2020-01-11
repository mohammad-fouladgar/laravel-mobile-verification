<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Repository\DatabaseTokenRepository;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Illuminate\Database\ConnectionInterface;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Mockery as m;

class RepositoryTest extends TestCase
{
    /** @test
     * @throws Exception
     */
    public function it_can_successfully_create_a_token()
    {
        $user         = new VerifiableUser();
        $user->mobile = '555555';

        $tokenRepository = new DatabaseTokenRepository(
            $connection = m::mock(ConnectionInterface::class)
        );

        $connection->shouldReceive('table')
                   ->andReturn(m::spy(Builder::class))
                   ->with('mobile_verification_tokens');

        $tokenLength = config('mobile_verifier.token_length');

        $this->assertEquals($tokenLength, Str::length($tokenRepository->create($user)));
    }
}
