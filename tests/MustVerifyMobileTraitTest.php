<?php

declare(strict_types=1);

namespace Fouladgar\MobileVerification\Tests;

use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;

class MustVerifyMobileTraitTest extends TestCase
{
    /** @test */
    public function it_checks_has_verified_mobile_method(): void
    {
        $user = VerifiableUser::factory()->make();
        $this->assertFalse($user->hasVerifiedMobile());

        $user = VerifiableUser::factory()->verified()->make();
        $this->assertTrue($user->hasVerifiedMobile());
    }

    /** @test */
    public function it_can_successfully_verify_a_user(): void
    {
        $user = VerifiableUser::factory()->create();

        $user->markMobileAsVerified();

        $this->assertTrue($user->hasVerifiedMobile());
    }
}
