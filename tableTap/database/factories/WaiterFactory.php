<?php

namespace Database\Factories;

use App\Models\Waiter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class WaiterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Waiter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->firstName;
        $surname = $this->faker->lastName;

        return [
            'name' => $name,
            'surname' => $surname,
            'username' => strtolower($name . '.' . $surname . rand(100, 999)),
            'password' => Hash::make('password123'), // Default password
        ];
    }
}