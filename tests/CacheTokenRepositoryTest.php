<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerification\Tokens\TokenRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CacheTokenRepositoryTest extends TestCase
{

    private TokenRepositoryInterface $repository;

    private VerifiableUser $user;

    public function setUp(): void
    {
        parent::setUp();

        app('config')->set('mobile_verifier.token_storage', 'cache');
        $this->repository = $this->app->make(TokenRepositoryInterface::class);

        $this->user = new VerifiableUser(['mobile' => '555555']);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_can_successfully_create_a_token(): void
    {
        $payload = ['mobile' => $this->user->mobile, 'sent_at' => now()->toDateTimeString()];
        $token = $this->repository->create($this->user);
        $payload['token'] = $token;

        $this->assertEquals(Cache::get($payload['mobile']), $payload);
    }

    /**
     * @test
     */
    public function it_can_return_latest_sent_at_as_empty_string_if_token_doesnt_exist()
    {
        $this->repository->setExpires(-5);
        $token = $this->repository->create($this->user);

        $this->assertEmpty($this->repository->latestSentAt($this->user, $token));
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
        $this->repository->create($this->user);

        $this->repository->deleteExisting($this->user);

        $this->assertNull(Cache::get($this->user->mobile));
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
