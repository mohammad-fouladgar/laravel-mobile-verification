<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Testing\TestResponse;
use Orchestra\Testbench\TestCase as BaseTestCase;
use ReflectionFunction;

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

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Fouladgar\\MobileVerification\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
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
                static fn() => '__passed__',
            ]
        )->uri();
    }

    /**
     * Call the given middleware using a JSON request.
     */
    protected function callMiddlewareJson(string|array $middleware, string $method = 'GET', array $data = []): TestResponse
    {
        return $this->json(
            $method,
            $this->makeMiddlewareRoute($method, $middleware),
            $data
        );
    }

    /**
     * Custom assertListening function for supporting Laravel < v8
     *
     * @param $expectedEvent
     * @param $expectedListener
     */
//    protected function assertListening($expectedEvent, $expectedListener)
//    {
//        $dispatcher = $this->app->make(Dispatcher::class);
//
//        foreach ($dispatcher->getListeners($expectedEvent) as $listenerClosure) {
//            $actualListener = (new ReflectionFunction($listenerClosure))
//                ->getStaticVariables()['listener'];
//
//            if ($actualListener === $expectedListener ||
//                ($actualListener instanceof Closure &&
//                    $expectedListener === Closure::class)) {
//                $this->assertTrue(true);
//
//                return;
//            }
//        }
//
//        $this->assertTrue(
//            false,
//            sprintf(
//                'Event [%s] does not have the [%s] listener attached to it',
//                $expectedEvent,
//                print_r($expectedListener, true)
//            )
//        );
//    }
}
