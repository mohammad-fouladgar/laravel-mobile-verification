<?php

namespace Fouladgar\MobileVerifier\Tests\Models;

use Fouladgar\MobileVerifier\Concerns\MustVerifyMobile as MustVerifyMobile;
use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile as IMustVerifyMobile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class VerifiableUser extends Model implements AuthenticatableContract,IMustVerifyMobile
{
    use Authenticatable, MustVerifyMobile;

    public $timestamps = false;

    protected $fillable = [
        'name', 'mobile'
    ];
    

}