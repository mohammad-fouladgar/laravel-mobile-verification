<?php

namespace Fouladgar\MobileVerification\Tests;

use Exception;
use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerification\Tokens\DatabaseTokenRepository;
use Illuminate\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use ReflectionMethod;

class TokenRepositoryTest extends TestCase
{
    /**
     * @var Builder
     */
    private $table;

    /**
     * @var DatabaseTokenRepository
     */
    private $tokenRepository;

    /**
     * @var Repository
     */
    private $tokenLength;

    /**
     * @var Repository
     */
    private $tokenLifetime;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $tokenRepository = new DatabaseTokenRepository(
            app(ConnectionInterface::class)
        );

        $method = new ReflectionMethod(DatabaseTokenRepository::class, 'getTable');
        $method->setAccessible(true);

        $this->table = $method->invoke($tokenRepository);
        $this->tokenRepository = $tokenRepository;
        $this->tokenLength = config('mobile_verifier.token_length');
        $this->tokenLifetime = config('mobile_verifier.token_lifetime');
    }

    /** @test
     * @throws Exception
     */
    public function it_can_successfully_create_a_token()
    {
        $user = new VerifiableUser();
        $user->mobile = '555555';

        $token = $this->tokenRepository->create($user);

        $this->assertEquals($this->tokenLength, Str::length($token));

        $this->assertDatabaseHas('mobile_verification_tokens', [
            'mobile'     => '555555',
            'token'      => $token,
            'expires_at' => (string) now()->addMinutes($this->tokenLifetime),
        ]);
    }

    /** @test
     * @throws Exception
     */
    public function it_can_successfully_delete_existing_token()
    {
        $user = new VerifiableUser();
        $user->mobile = '555555';

        $record = [
            'mobile'     => '555555',
            'token'      => 'token_123',
            'expires_at' => (string) now(),
        ];

        $this->table->insert($record);

        $this->tokenRepository->deleteExisting($user);

        $this->assertDatabaseMissing('mobile_verification_tokens', $record);
    }

    /** @test
     * @throws Exception
     */
    public function it_can_successfully_find_existing_and_not_expired_token()
    {
        $user = new VerifiableUser();
        $user->mobile = '555555';

        $record = [
            'mobile'     => '555555',
            'token'      => 'token_123',
            'expires_at' => (string) now(),
        ];

        $this->table->insert($record);

        $this->assertTrue($this->tokenRepository->exists($user, $record['token']));
    }

    /** @test
     * @throws Exception
     */
    public function it_fails_when_token_is_exist_but_expired()
    {
        $user = new VerifiableUser();
        $user->mobile = '555555';

        $record = [
            'mobile'     => '555555',
            'token'      => 'token_123',
            'expires_at' => (string) now()->subMinutes($this->tokenLifetime),
        ];

        $this->table->insert($record);

        $this->assertFalse($this->tokenRepository->exists($user, $record['token']));
    }

    /** @test
     * @throws Exception
     */
    public function it_fails_when_token_is_not_existed()
    {
        $user = new VerifiableUser();
        $user->mobile = '555555';

        $record = [
            'mobile'     => '555555',
            'token'      => 'token_123',
            'expires_at' => (string) now(),
        ];

        $this->table->insert($record);

        $this->assertFalse($this->tokenRepository->exists($user, 'token_123456'));
    }
}
