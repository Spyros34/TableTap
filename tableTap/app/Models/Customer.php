<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'surname', 'username', 'password', 'email', 'credit_card', 'address', 'city', 'region', 'tk'];

    public function table()
    {
        return $this->hasOne(Table::class, 'customer_table');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
