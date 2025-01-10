<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{

    use HasFactory;
    protected $fillable = [
        'table_num',
        'qr_code',
    ];

    public function shop()
    {
        return $this->belongsToMany(Shop::class, 'shop_table_association')
                    ->withTimestamps();
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_table')
                    ->withPivot('customer_id', 'table_id')
                    ->using(\Illuminate\Database\Eloquent\Relations\Pivot::class);
    }

    
    
}
