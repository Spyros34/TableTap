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
        return $this->belongsToMany(Kitchen::class, 'shop_kitchen');
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

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Table::class, 'shop_id', 'table_id', 'id', 'id');
    }

}
