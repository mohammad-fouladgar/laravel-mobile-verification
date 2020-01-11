<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerifier\Tests\Models\User;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MiddlwareTest extends TestCase
{
    /** @test */
    public function it_can3()
    {
        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        try {
            $middleware->handle($request, function ($request) {});
        } catch (HttpException $ex) {
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.', $ex->getMessage());

            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_can4()
    {
        $this->actingAs(
         factory(User::class)->make()
        );

        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        $response = $middleware->handle($request, function ($request) {});

        $this->assertNull($response);
    }

    /** @test */
    public function it_can5()
    {
        $this->actingAs(
            factory(VerifiableUser::class)->make()
        );

        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        try {
            $middleware->handle($request, function ($request) {});
        } catch (HttpException $ex) {
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.', $ex->getMessage());

            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_can6()
    {
        $this->actingAs(
            factory(VerifiableUser::class)->state('verified')->make()
        );

        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        $response = $middleware->handle($request, function ($request) {});

        $this->assertNull($response);
    }
}
