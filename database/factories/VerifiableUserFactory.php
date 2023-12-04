<?php

namespace Fouladgar\MobileVerification\Database\Factories;

use Carbon\Carbon;
use Fouladgar\MobileVerification\Tests\Models\VerifiableUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerifiableUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = VerifiableUser::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'               => $this->faker->name,
            'mobile'             => '555555',
            'mobile_verified_at' => null,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'mobile_verified_at' => Carbon::now(),
        ]);
    }
}

