<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Listeners\SendMobileVerificationNotificationQueueable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

class QueueableListenerTest extends TestCase
{
    /** @test **/
    public function it_should_be_queueable(): void
    {
        Event::assertListening(
            Registered::class,
            SendMobileVerificationNotificationQueueable::class
        );
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        Event::fake();

        $app['config']->set('mobile_verifier.queue.connection', 'database');
    }
}
