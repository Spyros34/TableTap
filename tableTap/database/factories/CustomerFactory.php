<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // Set a default hashed password
            'credit_card' => $this->faker->creditCardNumber,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'region' => $this->faker->state,
            'tk' => $this->faker->postcode,
        ];
    }
}