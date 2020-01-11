<?php

namespace Fouladgar\MobileVerifier\Tests\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name', 'mobile',
    ];
}
