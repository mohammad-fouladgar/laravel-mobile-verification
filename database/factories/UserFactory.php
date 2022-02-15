<?php

namespace Fouladgar\MobileVerification\Database\Factories;

use Fouladgar\MobileVerification\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     */
    public function definition(): array
    {
        return [
            'name'   => $this->faker->firstName(),
            'mobile' => $this->faker->phoneNumber(),
        ];
    }
}

