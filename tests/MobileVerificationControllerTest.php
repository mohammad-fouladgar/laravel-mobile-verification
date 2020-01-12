<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\TokenBroker;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Mockery as m;
use Symfony\Component\HttpFoundation\Response;

class MobileVerificationControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_verify_a_user()
    {
        $user = factory(VerifiableUser::class)->create();

        $this->actingAs($user);

        $tokenBroker = m::mock(TokenBroker::class);
        $tokenBroker->shouldReceive('verifyToken')->andReturn(true);

        $this->app->instance(TokenBroker::class, $tokenBroker);

        $response = $this->postJson(route('mobile.verified'), ['token' => '12345']);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function it_fails_on_verifing_a_user()
    {
        $user = factory(VerifiableUser::class)->create();

        $this->actingAs($user);

        $response = $this->postJson(route('mobile.verified'), ['token' => '12345']);

        $response->assertStatus(Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @test
     */
    public function it_failes_on_resend_when_user_is_already_verified()
    {
        $user = factory(VerifiableUser::class)->state('verified')->create();

        $this->actingAs($user);

        $response = $this->postJson(route('mobile.resend'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function it_can_resend_a_token()
    {
        $user = factory(VerifiableUser::class)->create();

        $this->actingAs($user);

        $response = $this->postJson(route('mobile.resend'));

        $response->assertStatus(Response::HTTP_OK);
    }
}
