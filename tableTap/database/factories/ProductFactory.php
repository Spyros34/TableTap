<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 100), // Random price between 1 and 100
            'description' => $this->faker->sentence,
            'availability' => $this->faker->boolean,
            'quantity' => 10,
        ];
    }
}