<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'availability',
        'quantity'
    ];


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_product')->withPivot('shop_id');
    }
}
