<?php

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Fouladgar\MobileVerification\Tokens\TokenBroker;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
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

        // Override service provider binding with mocked service binding
        $this->app->instance(TokenBrokerInterface::class, $tokenBroker);

        $this->postJson(route('mobile.verify'), ['token' => '12345'])
            ->assertOk()
            ->assertJson(['message' => __('MobileVerification::mobile_verifier.successful_verification')]);

        $this->post(route('mobile.verify'), ['token' => '12345'])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('mobileVerificationVerified');
    }

    /** @test */
    public function it_fails_on_verifying_when_user_has_already_verified()
    {
        $user = factory(VerifiableUser::class)->state('verified')->create();

        $this->actingAs($user);

        $this->postJson(route('mobile.verify'), ['token' => '12345'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => __('MobileVerification::mobile_verifier.already_verified')]);

        $this->post(route('mobile.verify'), ['token' => '12345'])
            ->assertStatus(Response::HTTP_FOUND);
    }

    /** @test */
    public function it_will_check_validation_for_token_verification()
    {
        $user = factory(VerifiableUser::class)->make();

        $this->actingAs($user);

        $this->postJson(route('mobile.verify'))
            ->assertJsonValidationErrors(['token'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->post(route('mobile.verify'))
            ->assertSessionHasErrors('token')
            ->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function it_fails_on_verifying_a_user()
    {
        $user = factory(VerifiableUser::class)->create();

        $this->actingAs($user);

        $this->postJson(route('mobile.verify'), ['token' => '12345'])
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE);

        $this->post(route('mobile.verify'), ['token' => '12345'])
            ->assertSessionHasErrors('token');
    }

    /**
     * @test
     */
    public function it_fails_on_resend_when_user_is_already_verified()
    {
        $user = factory(VerifiableUser::class)->state('verified')->create();

        $this->actingAs($user);

        $this->postJson(route('mobile.resend'))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => __('MobileVerification::mobile_verifier.already_verified'),
            ]);

        $this->post(route('mobile.resend'))
            ->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function it_can_resend_a_token()
    {
        $user = factory(VerifiableUser::class)->create();

        $this->actingAs($user);

        $this->postJson(route('mobile.resend'))
            ->assertOk();

        $this->post(route('mobile.resend'))
            ->assertSessionHas('mobileVerificationResend');
    }
}
