<?php

namespace Fouladgar\MobileVerifier;

use Fouladgar\MobileVerifier\Contracts\SmsClient;
use Fouladgar\MobileVerifier\Middleware\EnsureMobileIsVerified;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param Filesystem $filesystem
     */
    public function boot(Filesystem $filesystem): void
    {
        $this->bootPublishes($filesystem);

        $this->app['router']->middleware('mobile.verified', EnsureMobileIsVerified::class);
    }

    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfig(), 'mobile_verifier');

        $this->app->singleton(SmsClient::class, static function ($app) {
            return $app->make(config('mobile_verifier.sms_client'));
        });
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
                             return $filesystem->glob($path . '*_create_mobile_verifications_table.php');
                         })->push($this->app->databasePath() . "/migrations/{$timestamp}_create_mobile_verifications_table.php")
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
            __DIR__ . '/../database/migrations/create_mobile_verifications_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');
    }

    /**
     * @return string
     */
    protected function getConfig(): string
    {
        return __DIR__ . '/../config/config.php';
    }
}
