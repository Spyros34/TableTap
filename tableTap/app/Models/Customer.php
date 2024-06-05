<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'surname', 'username', 'email', 'password', 'credit_card', 'address', 'city', 'region', 'tk'
    ];

    public function table()
    {
        return $this->belongsToMany(Table::class, 'customer_table')
                    ->withPivot('customer_id', 'table_id')
                    ->using(\Illuminate\Database\Eloquent\Relations\Pivot::class);
    }
}
