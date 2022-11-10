<?php

namespace Fouladgar\MobileVerification;

use Fouladgar\MobileVerification\Contracts\SMSClient;
use Fouladgar\MobileVerification\Exceptions\SMSClientNotFoundException;
use Fouladgar\MobileVerification\Http\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerification\Tokens\TokenBroker;
use Fouladgar\MobileVerification\Tokens\TokenBrokerInterface;
use Fouladgar\MobileVerification\Tokens\TokenRepositoryInterface;
use Fouladgar\MobileVerification\Tokens\TokenRepositoryManager;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Throwable;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(Router $router): void
    {
        $this->registerRoutes();

        $this->loadAssetsFrom();

        $this->registerPublishing();

        $router->aliasMiddleware('mobile.verified', EnsureMobileIsVerified::class);
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);

        $this->mergeConfigFrom($this->getConfig(), 'mobile_verifier');

        $this->registerBindings();
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        Route::group(
            $this->routeConfiguration(),
            function (): void {
                $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
            }
        );
    }

    /**
     * Load and register package assets.
     */
    protected function loadAssetsFrom(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'MobileVerification');
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        $this->publishes([$this->getConfig() => config_path('mobile_verifier.php')], 'config');

        $this->publishes([__DIR__.'/../lang' => app()->langPath().'/vendor/MobileVerification'], 'lang');

        $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'migrations');
    }

    /**
     * Get the config file path.
     */
    protected function getConfig(): string
    {
        return __DIR__.'/../config/config.php';
    }

    /**
     * Register any package bindings.
     */
    protected function registerBindings(): void
    {
        $this->app->singleton(
            SMSClient::class,
            static function ($app) {
                try {
                    return $app->make(config('mobile_verifier.sms_client'));
                } catch (Throwable) {
                    throw new SMSClientNotFoundException();
                }
            }
        );

        $this->app->singleton('mobile.verifier.token.repository', fn($app) => new TokenRepositoryManager($app));

        $this->app->singleton(
            TokenRepositoryInterface::class,
            fn($app) => $app['mobile.verifier.token.repository']->driver()
        );

        $this->app->bind(TokenBrokerInterface::class, TokenBroker::class);
    }

    /**
     * Get route group configuration array.
     */
    private function routeConfiguration(): array
    {
        return [
            'namespace' => config(
                'mobile_verifier.controller_namespace',
                'Fouladgar\MobileVerification\Http\Controllers'
            ),
            'prefix'    => config('mobile_verifier.routes_prefix', 'auth'),
        ];
    }
}
