<?php

namespace Fouladgar\MobileVerification\Tests;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Fouladgar\MobileVerification\Tests\Models\User;
use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Fouladgar\MobileVerification\Http\Middleware\EnsureMobileIsVerified;

class MiddlewareTest extends TestCase
{
    /**
     * @var EnsureMobileIsVerified
     */
    private $middleware;

    /**
     * @var Request
     */
    private $jsonRequest;

    public function __construct()
    {
        parent::__construct();

        $this->middleware  = new EnsureMobileIsVerified();
        $this->jsonRequest = new Request();
        $this->jsonRequest->headers->set('Accept', 'application/json');
    }

    /** @test */
    public function it_fails_if_user_not_authenticated_and_request_is_ajax()
    {
        try {
            $this->middleware->handle($this->jsonRequest, static function ($request) {
            });
        } catch (HttpException $ex) {
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.', $ex->getMessage());

            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_fails_with_guest_user_and_not_ajax_request()
    {
        $response = $this->callMiddleware(EnsureMobileIsVerified::class);

        $response->assertRedirect('/');
    }

    /** @test */
    public function it_fails_if_user_is_not_verifiable()
    {
        $this->actingAs(factory(User::class)->make());

        $response = $this->middleware->handle($this->jsonRequest, static function ($request) {
        });

        $this->assertNull($response);
    }

    /** @test */
    public function it_fails_if_user_mobile_is_not_verified()
    {
        $this->actingAs(factory(VerifiableUser::class)->make());

        try {
            $this->middleware->handle($this->jsonRequest, static function ($request) {
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
        $this->actingAs(factory(VerifiableUser::class)->state('verified')->make());

        $response = $this->middleware->handle($this->jsonRequest, static function ($request) {
        });

        $this->assertNull($response);
    }
}
