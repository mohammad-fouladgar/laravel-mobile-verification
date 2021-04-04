<?php


declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Notifications\Factory\VerifyMobileNotificationFactory;
use Fouladgar\MobileVerification\Notifications\VerifyMobile;
use Fouladgar\MobileVerification\Notifications\VerifyMobileOnQueue;

class NotificationFactoryTest extends TestCase
{

    /** @test */
    public function it_should_return_verify_mobile_on_queue_if_use_queue_is_true(): void
    {
        app('config')->set('mobile_verifier.use_queue', true);
        $notification = VerifyMobileNotificationFactory::create("token");
        $this->assertInstanceOf(VerifyMobileOnQueue::class, $notification);
    }

    /** @test */
    public function it_should_return_verify_mobile_if_use_queue_is_false(): void
    {
        app('config')->set('mobile_verifier.use_queue', false);
        $notification = VerifyMobileNotificationFactory::create("token");
        $this->assertInstanceOf(VerifyMobile::class, $notification);
    }
}