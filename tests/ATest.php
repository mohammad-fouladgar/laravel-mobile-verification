<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Concerns\MustVerifyMobile;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile as ContractsMustVerifyMobile;
use Fouladgar\MobileVerifier\Listeners\SendMobileVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class  ATest extends TestCase
{
    /** @test */
    public function it_can_aa()
    {
        $user = new User();

        $user->mobile = '09389599530';

        $event = new Registered($user);
        $listener = new SendMobileVerificationNotification();
        $listener->handle($event);

        // $this->assertEquals( $listener->handle($event),'send successfuly.');
    }
}



class  User extends Model implements ContractsMustVerifyMobile
{
    use MustVerifyMobile,Notifiable;

}
