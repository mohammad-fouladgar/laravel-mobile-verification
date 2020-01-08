<?php

namespace Fouladgar\MobileVerifier\Tests;

use Fouladgar\MobileVerifier\Contracts\SmsClient;
use Fouladgar\MobileVerifier\Middleware\EnsureMobileIsVerified;
use Fouladgar\MobileVerifier\Notifications\Channels\VerificationChannel;
use Fouladgar\MobileVerifier\Notifications\VerifyMobile;
use Fouladgar\MobileVerifier\Tests\Models\User as ModelsUser;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Mockery as m;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class ATest extends TestCase
{

    /** @test */
    public function it_can()
    {
        $notification = new VerifyMobile();
        $notifiable = new UserNotifiable();

        $verificationChannel = new VerificationChannel(
            $client = m::mock(SmsClient::class)
        );

        $client->shouldReceive('sendMessage')
                ->once()
                ->andReturn(true);

        $this->assertTrue($verificationChannel->send($notifiable,$notification));
    }

    /** @test */
    public function it_can2()
    {
        $notification = new VerifyMobile();
        $notifiable = new User();

        $verificationChannel = new VerificationChannel(
            $client = m::mock(SmsClient::class)
        );

        $client->shouldNotReceive('sendMessage');
       
        $this->assertNull($verificationChannel->send($notifiable,$notification));
    }

    /** @test */
    public function it_can3()
    {
       $request = new Request();

       $middleware = new EnsureMobileIsVerified();

       try {
            $middleware->handle($request,function($request){});
        } catch (HttpException $ex) {
            
            $this->assertEquals(Response::HTTP_FORBIDDEN,$ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.',$ex->getMessage());
            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_can4()
    {
      
        $this->actingAs(
         factory(ModelsUser::class)->make()
        );

        $request = new Request();

       $middleware = new EnsureMobileIsVerified();

        $response = $middleware->handle($request,function($request){});

        $this->assertNull($response);
    }

    /** @test */
    public function it_can5()
    {
        $this->actingAs(
            factory(VerifiableUser::class)->make()
        );
   
        $request = new Request();

        $middleware = new EnsureMobileIsVerified();

        try {
            $middleware->handle($request,function($request){});
        } catch (HttpException $ex) {
            
            $this->assertEquals(Response::HTTP_FORBIDDEN,$ex->getStatusCode());
            $this->assertEquals('Your mobile number is not verified.',$ex->getMessage());
            return;
        }

        $this->fail('Expected a 403 forbidden');
    }

    /** @test */
    public function it_can6()
    {
        $this->actingAs(
            factory(VerifiableUser::class)->state('verified')->make()
        );
   
           $request = new Request();
   
          $middleware = new EnsureMobileIsVerified();
   
           $response = $middleware->handle($request,function($request){});
   
           $this->assertNull($response);
    }
}


class UserNotifiable
{
    use Notifiable;

    public $mobile = '2222222';

    public function routeNotificationForVerificationMobile($notification)
    {
        return $this->mobile;
    }
}

class User
{
    use Notifiable;

    public $mobile = '2222222';
}
