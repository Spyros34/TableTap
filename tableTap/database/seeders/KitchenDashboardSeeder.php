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
use App\Models\Waiter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KitchenDashboardSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('customer_table')->truncate();
        DB::table('shop_table_association')->truncate();
        DB::table('tables')->truncate();
        DB::table('customers')->truncate();
        DB::table('shop_product')->truncate();
        DB::table('kitchens')->truncate();
        DB::table('owner_shop')->truncate();
        DB::table('shops')->truncate();
        DB::table('owners')->truncate();
        DB::table('shop_waiter')->truncate();
        DB::table('waiters')->truncate();

        // Re-enable foreign key checks after truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create multiple owners and their shops
        $owners = collect();
        for ($i = 1; $i <= 3; $i++) {
            $owner = Owner::create([
                'username' => "OwnerUser$i",
                'name' => "Owner$i",
                'surname' => "User$i",
                'email' => "owner$i@example.com",
                'password' => Hash::make("password$i"),
            ]);

            $shop = Shop::create([
                'brand' => "Shop{$i}",
                'address' => "123 Test Street {$i}",
                'status' => 1,
                'phone_number' => "123456789{$i}",
                'tk' => '11111',
                'city' => "Test City {$i}",
                'region' => "Test Region {$i}",
                'type' => 'Restaurant',
            ]);

            // Associate the owner with their shop
            $owner->shops()->attach($shop->id);

            // Create Kitchens for each shop
            for ($j = 1; $j <= 2; $j++) {
                Kitchen::create([
                    'name' => "Kitchen_$j", // Shared names across shops
                    'password' => Hash::make('password123'),
                    'shop_id' => $shop->id,
                ]);
            }

            // Create Tables and associate them with the shop
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

            // Create Products and associate with the shop
            $products = Product::factory()->count(5)->create();
            $products->each(function ($product) use ($shop) {
                DB::table('shop_product')->insertOrIgnore([
                    'shop_id' => $shop->id,
                    'product_id' => $product->id,
                ]);
            });

            // Create Waiters and associate with the shop
            $waiters = Waiter::factory()->count(3)->create();
            $waiters->each(function ($waiter) use ($shop) {
                DB::table('shop_waiter')->insertOrIgnore([
                    'shop_id' => $shop->id,
                    'waiter_id' => $waiter->id,
                ]);
            });

            // Create Orders and Order Items
            $customers->each(function ($customer) use ($products) {
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'status' => 'pending',
                ]);

                // Create Order Items
                foreach ($products->take(3) as $product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'amount' => rand(1, 5),
                    ]);
                }
            });
        }
    }
}