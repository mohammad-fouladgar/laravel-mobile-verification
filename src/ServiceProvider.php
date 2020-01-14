<?php

namespace Fouladgar\MobileVerifier;

use Fouladgar\MobileVerifier\Concerns\TokenBroker;
use Fouladgar\MobileVerifier\Contracts\SmsClient;
use Fouladgar\MobileVerifier\Contracts\TokenBrokerInterface;
use Fouladgar\MobileVerifier\Contracts\TokenRepositoryInterface;
use Fouladgar\MobileVerifier\Exceptions\SMSClientNotFoundException;
use Fouladgar\MobileVerifier\Http\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerifier\Repository\DatabaseTokenRepository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Default namespace for controllers
     *
     * @var string
     */
    protected $namespace = 'Fouladgar\MobileVerifier\Http\Controllers';

    /**
     * Perform post-registration booting of services.
     *
     * @param Filesystem $filesystem
     */
    public function boot(Filesystem $filesystem): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'MobileVerifier');

        $this->bootPublishes($filesystem);

        $this->app['router']->aliasMiddleware('mobile.verified', EnsureMobileIsVerified::class);
    }

    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);

        $this->mergeConfigFrom($this->getConfig(), 'mobile_verifier');

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
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
                         ->flatMap(static function ($path) use ($filesystem) {
                             return $filesystem->glob($path . '*_create_mobile_verification_tokens_table.php');
                         })->push($this->app->databasePath() . "/migrations/{$timestamp}_create_mobile_verification_tokens_table.php")
                         ->first();
    }

    /**
     * @param Filesystem $filesystem
     * @return void
     */
    protected function bootPublishes(Filesystem $filesystem): void
    {
        $this->publishes([
            $this->getConfig() => config_path('mobile_verifier.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/MobileVerifier'),
        ]);

        $this->publishes([
            __DIR__ . '/../database/migrations/create_mobile_verification_tokens_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');
    }

    /**
     * @return string
     */
    protected function getConfig(): string
    {
        return __DIR__ . '/../config/config.php';
    }

    /**
     * Map routes
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('auth')
             ->namespace($this->namespace)
             ->group(__DIR__ . '/Http/routes.php');
    }
}
