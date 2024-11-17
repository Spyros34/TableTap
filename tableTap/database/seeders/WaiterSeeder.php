<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Waiter;
use App\Models\Shop;

class WaiterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve all shops
        $shops = Shop::all();

        if ($shops->isEmpty()) {
            $this->command->warn("No shops found. Please seed shops first.");
            return;
        }

        // Create waiters for each shop
        foreach ($shops as $shop) {
            // Number of waiters per shop
            $waitersPerShop = rand(2, 5);

            for ($i = 0; $i < $waitersPerShop; $i++) {
                $waiter = Waiter::create([
                    'name' => fake()->firstName(),
                    'surname' => fake()->lastName(),
                    'username' => fake()->unique()->userName(),
                    'password' => Hash::make('password'), // Default password
                ]);

                // Associate waiter with the shop
                $shop->waiters()->attach($waiter->id, ['created_at' => now()]);
            }
        }

        $this->command->info("Waiters seeded successfully.");
    }
}