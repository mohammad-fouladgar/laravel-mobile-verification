<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;
use Fouladgar\MobileVerifier\Tests\Models\User;
use Fouladgar\MobileVerifier\Tests\Models\VerifiableUser;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(VerifiableUser::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'mobile' => '555555',
        'mobile_verified_at' => null
    ];
});

$factory->state(VerifiableUser::class,'verified',[
    'mobile_verified_at' => Carbon::now()
]);