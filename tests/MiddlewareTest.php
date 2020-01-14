<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Http\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerifier\Tests\Models\User;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MiddlewareTest extends TestCase
{
    /** @test */
    public function it_fails_if_user_not_authenticated()
    {
        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        try {
            $middleware->handle($request, static function ($request) {
            });
        } catch (HttpException $ex) {
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.', $ex->getMessage());

            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_fails_if_user_is_not_verifiable()
    {
        $this->actingAs(
            factory(User::class)->make()
        );

        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        $response = $middleware->handle($request, static function ($request) {
        });

        $this->assertNull($response);
    }

    /** @test */
    public function it_fails_if_user_mobile_is_not_verified()
    {
        $this->actingAs(
            factory(VerifiableUser::class)->make()
        );

        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        try {
            $middleware->handle($request, static function ($request) {
            });
        } catch (HttpException $ex) {
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.', $ex->getMessage());

            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_pass_the_request_successfully()
    {
        $this->actingAs(
            factory(VerifiableUser::class)->state('verified')->make()
        );

        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        $response = $middleware->handle($request, static function ($request) {
        });

        $this->assertNull($response);
    }
}
