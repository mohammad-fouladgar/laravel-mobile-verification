<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\ServiceProvider;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ThrottleRequests::class);

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('mobile_verifier.sms_client', SampleSMSClient::class);
    }

    /**
     * Call the given middleware.
     *
     * @param string|string[] $middleware
     * @param string $method
     * @param array $data
     */
    protected function callMiddleware($middleware, string $method = 'GET', array $data = [])
    {
        return $this->call(
            $method,
            $this->makeMiddlewareRoute($method, $middleware),
            $data
        );
    }

    /**
     * Make a dummy route with the given middleware applied.
     *
     * @param string $method
     * @param string|string[] $middleware
     */
    protected function makeMiddlewareRoute(string $method, $middleware): string
    {
        $method = strtolower($method);

        return $this->app->make('router')->{$method}(
            '/__middleware__',
            [
                'middleware' => $middleware,
                static function () {
                    return '__passed__';
                },
            ]
        )->uri();
    }

    /**
     * Call the given middleware using a JSON request.
     *
     * @param string|string[] $middleware
     * @param string $method
     * @param array $data
     */
    protected function callMiddlewareJson($middleware, string $method = 'GET', array $data = [])
    {
        return $this->json(
            $method,
            $this->makeMiddlewareRoute($method, $middleware),
            $data
        );
    }
}
