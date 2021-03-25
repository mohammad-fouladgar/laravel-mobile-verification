<?php

namespace Fouladgar\MobileVerification;

use Fouladgar\MobileVerification\Contracts\SMSClient;
use Fouladgar\MobileVerification\Exceptions\SMSClientNotFoundException;
use Fouladgar\MobileVerification\Http\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerification\Tokens\CacheTokenRepository;
use Fouladgar\MobileVerification\Tokens\DatabaseTokenRepository;
use Fouladgar\MobileVerification\Tokens\TokenBroker;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Fouladgar\MobileVerification\Tokens\TokenRepositoryInterface;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Throwable;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param  Router  $router
     */
    public function boot(Router $router): void
    {
        $this->registerRoutes();

        $this->loadAssetsFrom();

        $this->registerPublishing();

        $router->aliasMiddleware('mobile.verified', EnsureMobileIsVerified::class);
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::group(
            $this->routeConfiguration(),
            function () {
                $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
            }
        );
    }

    /**
     * Get the Telescope route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration(): array
    {
        return [
            'namespace' => config(
                'mobile_verifier.controller_namespace',
                'Fouladgar\MobileVerification\Http\Controllers'
            ),
            'prefix' => config('mobile_verifier.routes_prefix', 'auth'),
        ];
    }

    /**
     * Load and register package assets.
     */
    protected function loadAssetsFrom(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'MobileVerification');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        $this->publishes([$this->getConfig() => config_path('mobile_verifier.php')], 'config');

        $this->publishes(
            [
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/MobileVerification'),
            ],
            'lang'
        );

        $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'migrations');
    }

    /**
     * Get the config file path.
     *
     * @return string
     */
    protected function getConfig(): string
    {
        return __DIR__.'/../config/config.php';
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);

        $this->mergeConfigFrom($this->getConfig(), 'mobile_verifier');

        $this->registerBindings();
    }

    /**
     * Register any package bindings.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        $this->app->singleton(
            SMSClient::class,
            static function ($app) {
                try {
                    return $app->make(config('mobile_verifier.sms_client'));
                } catch (Throwable $e) {
                    throw new SMSClientNotFoundException();
                }
            }
        );

        $this->app->bind(
            TokenRepositoryInterface::class,
            static function ($app) {
                switch (config('mobile_verifier.token_storage', 'database')) {
                    case 'database':
                        return new DatabaseTokenRepository(
                            config('mobile_verifier.token_lifetime', 5),
                            config('mobile_verifier.token_length', 5),
                            config(
                                'mobile_verifier.token_table',
                                'mobile_verification_tokens'
                            ),
                            $app->make(ConnectionInterface::class)
                        );
//
                        break;
                    case 'cache':
                        return new CacheTokenRepository(
                            config('mobile_verifier.token_lifetime', 5),
                            config('mobile_verifier.token_length', 'mobile_verification_tokens')
                        );

                        break;
                    default:
                }
            }
        );

        $this->app->bind(TokenBrokerInterface::class, TokenBroker::class);
    }
}
