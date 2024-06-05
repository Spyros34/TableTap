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
}