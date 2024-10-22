<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kitchen;
use Illuminate\Support\Facades\Hash;

class KitchenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add multiple kitchen records using a loop
        for ($i = 1; $i <= 10; $i++) {
            Kitchen::create([
                'name' => 'This is the Kitchen ' . $i,
                'password' => Hash::make('password' . $i), // Example: password1, password2, etc.
            ]);
        }
        
        // Alternatively, you can add multiple records using an array
        Kitchen::insert([
            ['name' => 'Kitchen 11', 'password' => Hash::make('password11')],
            ['name' => 'Kitchen 12', 'password' => Hash::make('password12')],
            ['name' => 'Kitchen 13', 'password' => Hash::make('password13')],
        ]);
    }
}
