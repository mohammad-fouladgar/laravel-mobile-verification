<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Fouladgar\MobileVerifier\Tests\Models\User;
use Illuminate\Database\Eloquent\Factory;

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

$factory->define(User::class, static function (Faker $faker) {
    return [
        'name'   => $faker->name,
        'mobile' => '555555',
    ];
});
