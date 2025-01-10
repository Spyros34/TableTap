<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Table;
use App\Models\Kitchen;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Owner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KitchenDashboardSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear previous data safely using truncate
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('customer_table')->truncate();
        DB::table('shop_table_association')->truncate();
        DB::table('tables')->truncate();
        DB::table('customers')->truncate();
        DB::table('shop_product')->truncate();
        DB::table('shop_kitchen')->truncate();
        DB::table('owner_shop')->truncate();

        // Create or Retrieve an Owner
        $owner = Owner::firstOrCreate(
            ['username' => 'OwnerUser'],
            [
                'name' => 'Owner',
                'surname' => 'User',
                'email' => 'owner@example.com',
                'password' => Hash::make('password123'),
            ]
        );

        // Create or Retrieve a Kitchen user
        $kitchen = Kitchen::firstOrCreate(
            ['name' => 'KitchenUser2'],
            ['password' => Hash::make('password123')]
        );

        // Create or Retrieve a Shop
        $shop = Shop::firstOrCreate(
            ['brand' => 'Test Shop'],
            [
                'address' => '123 Test Street',
                'status' => 1,
                'phone_number' => '1234567890',
                'tk' => '11111',
                'city' => 'Test City',
                'region' => 'Test Region',
                'type' => 'Restaurant',
            ]
        );

        // Associate the owner with the shop
        $owner->shops()->syncWithoutDetaching([$shop->id]);

        // Associate the kitchen with the shop
        $kitchen->shops()->syncWithoutDetaching([$shop->id]);

        // Create Tables and associate them with the Shop
        $tables = Table::factory()->count(3)->create();
        $tables->each(function ($table) use ($shop) {
            DB::table('shop_table_association')->insertOrIgnore([
                'shop_id' => $shop->id,
                'table_id' => $table->id,
            ]);
        });

        // Create Customers
        $customers = Customer::factory()->count(10)->create();

        // Assign customers to tables
        $customers->each(function ($customer, $index) use ($tables) {
            DB::table('customer_table')->insertOrIgnore([
                'customer_id' => $customer->id,
                'table_id' => $tables[$index % $tables->count()]->id,
            ]);
        });

        // Create Products and associate them with the Shop
        $products = Product::factory()->count(5)->create();
        $products->each(function ($product) use ($shop) {
            DB::table('shop_product')->insertOrIgnore([
                'shop_id' => $shop->id,
                'product_id' => $product->id,
            ]);
        });

        // Create Orders and Order Items
        $customers->each(function ($customer) use ($products) {
            $order = Order::create([
                'customer_id' => $customer->id,
                'status' => 'pending',
            ]);

            // Create Order Items for the order
            foreach ($products->take(3) as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'amount' => rand(1, 5),
                ]);
            }
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}