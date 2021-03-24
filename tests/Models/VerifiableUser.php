<?php

namespace Fouladgar\MobileVerification\Tests\Models;

use Fouladgar\MobileVerification\Concerns\MustVerifyMobile;
use Fouladgar\MobileVerification\Contracts\MustVerifyMobile as IMustVerifyMobile;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class VerifiableUser extends Model implements AuthenticatableContract, IMustVerifyMobile
{
    use Authenticatable;
    use MustVerifyMobile;
    use Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
}
