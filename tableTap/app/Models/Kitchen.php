<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model
{
    protected $fillable = ['name', 'password'];

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_kitchen');
    }
}
