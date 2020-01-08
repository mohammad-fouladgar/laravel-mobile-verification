<?php

namespace Fouladgar\MobileVerifier\Tests\Models;

use Fouladgar\MobileVerifier\Contracts\MustVerifyMobile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    public $timestamps = false;

    protected $fillable = [
        'name', 'mobile'
    ];
    

}