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


}
