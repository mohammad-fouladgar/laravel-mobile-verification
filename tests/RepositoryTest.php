<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Repository\DatabaseTokenRepository;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Illuminate\Database\ConnectionInterface;
use Exception;
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
            app(ConnectionInterface::class)
        );

        $tokenLength = config('mobile_verifier.token_length');

        $tokenLifetime = config('mobile_verifier.token_lifetime');

        $token = $tokenRepository->create($user);

        $this->assertEquals($tokenLength, Str::length($token));

        $this->assertDatabaseHas('mobile_verification_tokens', [
            'mobile'     => '555555',
            'token'      => $token,
            'expires_at' => (string)now()->addMinutes($tokenLifetime)
        ]);
    }

    /** @test
     * @throws Exception
     */
    public function it_can_successfully_delete_existing_token()
    {
        $user         = new VerifiableUser();
        $user->mobile = '555555';

        $tokenRepository = new DatabaseTokenRepository(
            app(ConnectionInterface::class)
        );

        $record = [
            'mobile'     => '555555',
            'token'      => 'token_123',
            'expires_at' => (string)now()
        ];

        $tokenRepository->getTable()->insert($record);

        $tokenRepository->deleteExisting($user);

        $this->assertDatabaseMissing('mobile_verification_tokens', $record);
    }
}
