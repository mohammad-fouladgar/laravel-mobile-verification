<?php

namespace Fouladgar\MobileVerifier\Tests\Models;

use Fouladgar\MobileVerifier\Concerns\MustVerifyMobile as MustVerifyMobile;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile as IMustVerifyMobile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class VerifiableUser extends Model implements AuthenticatableContract,IMustVerifyMobile
{
    use Authenticatable, MustVerifyMobile, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name', 'mobile'
    ];

}