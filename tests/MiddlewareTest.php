<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Http\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerification\Tests\Models\User;
use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

        $this->middleware = new EnsureMobileIsVerified();
        $this->jsonRequest = new Request();
        $this->jsonRequest->headers->set('Accept', 'application/json');
    }

    /** @test */
    public function it_fails_if_user_not_authenticated_and_request_is_ajax(): void
    {
        try {
            $this->middleware->handle($this->jsonRequest, static function ($request): void {
            });
        } catch (HttpException $ex) {
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.', $ex->getMessage());

            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_fails_with_guest_user_and_not_ajax_request(): void
    {
        $response = $this->callMiddleware(EnsureMobileIsVerified::class);

        $response->assertRedirect('/');
    }

    /** @test */
    public function it_fails_if_user_is_not_verifiable(): void
    {
        $this->actingAs(factory(User::class)->make());

        $response = $this->middleware->handle($this->jsonRequest, static function ($request): void {
        });

        $this->assertNull($response);
    }

    /** @test */
    public function it_fails_if_user_mobile_is_not_verified(): void
    {
        $this->actingAs(factory(VerifiableUser::class)->make());

        try {
            $this->middleware->handle($this->jsonRequest, static function ($request): void {
            });
        } catch (HttpException $ex) {
            $this->assertEquals(Response::HTTP_FORBIDDEN, $ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.', $ex->getMessage());

            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_pass_the_request_successfully(): void
    {
        $this->actingAs(factory(VerifiableUser::class)->state('verified')->make());

        $response = $this->middleware->handle($this->jsonRequest, static function ($request): void {
        });

        $this->assertNull($response);
    }
}
