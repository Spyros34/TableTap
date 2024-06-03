<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'table_num',
        'qr_code',
    ];

    public function shop()
    {
        return $this->belongsToMany(Shop::class, 'shop_table_association')
                    ->withTimestamps();
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_table');
    }
    
}
