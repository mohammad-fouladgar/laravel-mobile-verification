<?php

namespace Fouladgar\MobileVerifier;

use Fouladgar\MobileVerifier\Concerns\TokenBroker;
use Fouladgar\MobileVerifier\Contracts\SmsClient;
use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;
use Fouladgar\MobileVerifier\Exceptions\SMSClientNotFoundException;
use Fouladgar\MobileVerifier\Http\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerifier\Repository\DatabaseTokenRepository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param Filesystem $filesystem
     * @param Router     $router
     */
    public function boot(Filesystem $filesystem, Router $router): void
    {
        $this->registerRoutes();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'MobileVerifier');

        $this->registerPublishing($filesystem);

        $router->aliasMiddleware('mobile.verified', EnsureMobileIsVerified::class);
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

        $this->registerBidings();
    }

    /**
     * Register any package bindings.
     *
     * @return void
     */
    protected function registerBidings(): void
    {
        $this->app->singleton(SmsClient::class, static function ($app) {
            try {
                return $app->make(config('mobile_verifier.sms_client'));
            } catch (\Throwable $e) {
                throw new SMSClientNotFoundException();
            }
        });

        $this->app->bind(TokenRepositoryInterface::class, static function ($app) {
            return new DatabaseTokenRepository(
                $app->make(ConnectionInterface::class),
                config('mobile_verifier.token_table', 'mobile_verification_tokens'),
                config('mobile_verifier.token_lifetime', 5)
            );
        });

        $this->app->bind(TokenBrokerInterface::class, TokenBroker::class);
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     *
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
                         ->flatMap(static function ($path) use ($filesystem) {
                             return $filesystem->glob($path.'*_create_mobile_verification_tokens_table.php');
                         })->push($this->app->databasePath()."/migrations/{$timestamp}_create_mobile_verification_tokens_table.php")
                         ->first();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing(Filesystem $filesystem): void
    {
        $this->publishes([
            $this->getConfig() => config_path('mobile_verifier.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/MobileVerifier'),
        ]);

        $this->publishes([
            __DIR__.'/../database/migrations/create_mobile_verification_tokens_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');
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
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    /**
     * Get the Telescope route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration(): array
    {
        return [
            'namespace' => config('mobile_verifier.controller_namespace', 'Fouladgar\MobileVerifier\Http\Controllers'),
            'prefix'    => config('mobile_verifier.routes_prefix', 'auth'),
        ];
    }
}
