<?php

namespace Fouladgar\MobileVerifier;

use Fouladgar\MobileVerifier\Concerns\SmsClient;
use Fouladgar\MobileVerifier\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerifier\Notifications\Channels\VerificationChannel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

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
    public function register()
    {

        $configPath = __DIR__ . '/../config/config.php';

        $this->mergeConfigFrom($configPath, 'mobile_verifier');

        $this->app->singleton(SmsClient::class,static function($app) {
            return $app->make(config('mobile_verifier.sms_client'));
        });
        // Notification::resolved(function (ChannelManager $service) {
        //     $service->extend('nexmo', function ($app) {
        //         return new Channels\NexmoSmsChannel(
        //             $this->app->make(NexmoClient::class),
        //             $this->app['config']['services.nexmo.sms_from']
        //         );
        //     });
        //     $service->extend('shortcode', function ($app) {
        //         $client = tap($app->make(NexmoMessageClient::class), function ($client) use ($app) {
        //             $client->setClient($app->make(NexmoClient::class));
        //         });
        //         return new Channels\NexmoShortcodeChannel($client);
        //     });
        // });
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
        $configPath = __DIR__ . '/../config/config.php';

        $this->mergeConfigFrom($configPath, 'mobile_verifier');

        $this->publishes([
            $configPath => config_path('mobile_verifier.php')
        ], 'config');


        $this->publishes([
            __DIR__ . '/../database/migrations/create_mobile_verifications_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');
    }
}
