<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Table;

class TableFactory extends Factory
{
    protected $model = Table::class;

    public function definition()
    {
        return [
            'table_num' => $this->faker->unique()->numberBetween(1, 100), // Ensure uniqueness
            'qr_code' => $this->faker->uuid,
        ];
    }
}