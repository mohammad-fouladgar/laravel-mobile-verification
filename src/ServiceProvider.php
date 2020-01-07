<?php

namespace Fouladgar\MobileVerifier;

use Fouladgar\MobileVerifier\Middleware\EnsureMobileIsVerified;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(Filesystem $filesystem)
    {
        $this->bootPublishes($filesystem);

        $this->app['router']->middleware('mobile.verified', EnsureMobileIsVerified::class);
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
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
        $configPath = $this->configPath();

        $this->mergeConfigFrom($configPath, 'mobile_verifier');

        $this->publishes([$configPath => config_path('mobile_verifier.php')], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_mobile_verifications_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');
    }

    /**
     *
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__ . '/../config/mobile_verifier.php';
    }
}
