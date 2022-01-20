<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerification\Tokens\TokenRepositoryInterface;
use Illuminate\Support\Str;

class DatabaseTokenRepositoryTest extends TestCase
{
    /**
     * @var TokenRepositoryInterface
     */
    private $repository;

    /**
     * @var VerifiableUser
     */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        app('config')->set('mobile_verifier.token_storage', 'database');
        $this->repository = $this->app->make(TokenRepositoryInterface::class);

        $this->user = new VerifiableUser(['mobile' => '555555']);
    }

    /**
     * @test
     */
    public function it_can_successfully_create_a_token(): void
    {
        $tokenLifetime = config('mobile_verifier.token_lifetime');
        $tokenLength = config('mobile_verifier.token_length');

        $token = $this->repository->create($this->user);

        $this->assertEquals($tokenLength, Str::length($token));

        $this->assertDatabaseHas('mobile_verification_tokens', [
            'mobile' => $this->user->mobile,
            'token' => $token,
            'expires_at' => (string) now()->addMinutes($tokenLifetime),
        ]);
    }

    /**
     * @test
     */
    public function it_can_return_latest_sent_at_as_empty_string_if_token_doesnt_exist()
    {
        $this->repository->create($this->user);

        $this->assertEmpty($this->repository->latestSentAt($this->user, 'invalid_token'));
    }

    /**
     * @test
     */
    public function it_can_return_latest_sent_at_as_date_time_string_if_token_exists()
    {
        $token = $this->repository->create($this->user);

        $this->assertEquals(now()->toDateTimeString(), $this->repository->latestSentAt($this->user, $token));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_successfully_delete_existing_token(): void
    {
        $token = $this->repository->create($this->user);

        $this->repository->deleteExisting($this->user);

        $record = [
            'mobile' => $this->user->mobile,
            'token' => $token,
        ];

        $this->assertDatabaseMissing('mobile_verification_tokens', $record);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_successfully_find_existing_and_not_expired_token(): void
    {
        $token = $this->repository->create($this->user);

        $this->assertTrue($this->repository->exists($this->user, $token));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_fails_when_token_is_exist_but_expired(): void
    {
        $this->repository->setExpires(-5);
        $token = $this->repository->create($this->user);

        $this->assertFalse($this->repository->exists($this->user, $token));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_fails_when_token_is_not_existed(): void
    {
        $this->repository->create($this->user);

        $this->assertFalse($this->repository->exists($this->user, 'token_123456'));
    }
}
