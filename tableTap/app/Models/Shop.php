<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{


    protected $fillable = ['brand', 'address', 'status', 'phone_number', 'tk', 'city', 'region', 'type'];

    public function owners()
    {
        return $this->belongsToMany(Owner::class, 'owner_shop');
    }

    public function kitchens()
    {
        return $this->hasMany(Kitchen::class, 'shop_id');
    }

    public function tables()
    {
        return $this->belongsToMany(Table::class, 'shop_table_association')
                    ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'shop_product')->withPivot('product_id');
    }

    // Define the relationship with Waiter
    public function waiters()
    {
        return $this->belongsToMany(Waiter::class, 'shop_waiter')
                    ->withTimestamps();
    }

    public function getOrdersAttribute()
    {
        // 1) Gather all tables for this shop
        // 2) Flatten each table's orders
        // 3) Remove duplicates by ID (in case any overlap)
        return $this->tables
            ->flatMap(fn($table) => $table->orders)
            ->unique('id');
    }

}
