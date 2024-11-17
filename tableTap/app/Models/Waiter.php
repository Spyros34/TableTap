<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waiter extends Model
{
    protected $fillable = ['name', 'surname', 'username', 'password'];

    public function order()
    {
        return $this->hasOne(Order::class);
    }
    // Define the relationship with Shop
    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_waiter')
                    ->withTimestamps();
    }
}