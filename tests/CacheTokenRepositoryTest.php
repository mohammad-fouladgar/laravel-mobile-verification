<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerification\Tokens\CacheTokenRepository;
use Fouladgar\MobileVerification\Tokens\TokenRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use ReflectionMethod;

class CacheTokenRepositoryTest extends TestCase
{
    private TokenRepositoryInterface $tokenRepository;

    private ReflectionMethod $insertIntoStorageDriver;

    public function setUp(): void
    {
        parent::setUp();

        app('config')->set('mobile_verifier.token_storage', 'cache');

        Cache::flush();

        $this->insertIntoStorageDriver = new ReflectionMethod(CacheTokenRepository::class, 'insertIntoStorageDriver');
        $this->insertIntoStorageDriver->setAccessible(true);
        $this->tokenRepository = $this->app->make(TokenRepositoryInterface::class);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_successfully_create_a_token(): void
    {
        $payload = ['mobile' => '093895999530'];
        $user = new VerifiableUser($payload);
        $token = $this->tokenRepository->create($user);
        $payload['token'] = $token;

        $this->assertEquals(Cache::get($payload['mobile']), $payload);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_successfully_delete_existing_token(): void
    {
        $user = new VerifiableUser(['mobile' => '555555']);

        $record = [
            'mobile' => '555555',
            'token' => 'token_123',
        ];

        $this->insertIntoStorageDriver->invoke($this->tokenRepository, $record['mobile'], $record['token']);

        $this->tokenRepository->deleteExisting($user);

        $this->assertNull(Cache::get($record['mobile']));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_successfully_find_existing_and_not_expired_token(): void
    {
        $user = new VerifiableUser(['mobile' => '555555']);

        $record = [
            'mobile' => '555555',
            'token' => 'token_123',
        ];

        $this->insertIntoStorageDriver->invoke($this->tokenRepository, $record['mobile'], $record['token']);

        $this->assertTrue($this->tokenRepository->exists($user, $record['token']));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_fails_when_token_is_exist_but_expired(): void
    {
        $user = new VerifiableUser(['mobile' => '555555']);

        $record = [
            'mobile' => '555555',
            'token' => 'token_123',
        ];

        $this->tokenRepository->setExpires(-5);
        $this->insertIntoStorageDriver->invoke($this->tokenRepository, $record['mobile'], $record['token']);

        $this->assertFalse($this->tokenRepository->exists($user, $record['token']));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_fails_when_token_is_not_existed(): void
    {
        $user = new VerifiableUser(['mobile' => '555555']);

        $record = [
            'mobile' => '555555',
            'token' => 'token_123',
        ];

        $this->insertIntoStorageDriver->invoke($this->tokenRepository, $record['mobile'], $record['token']);

        $this->assertFalse($this->tokenRepository->exists($user, 'token_123456'));
    }
}
