<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;

class MustVerifyMobileTraitTest extends TestCase
{
    /** @test */
    public function it_checks_has_verified_mobile_method()
    {
        $user = factory(VerifiableUser::class)->make();

        $this->assertFalse($user->hasVerifiedMobile());

        $user = factory(VerifiableUser::class)->state('verified')->make();

        $this->assertTrue($user->hasVerifiedMobile());
    }

    /** @test */
    public function it_can_successfully_verify_a_user()
    {
        $user = factory(VerifiableUser::class)->create();

        $user->markMobileAsVerified();

        $this->assertTrue($user->hasVerifiedMobile());
    }
}
