<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = ['name', 'email', 'surname', 'username', 'password'];

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'owner_shop');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

}
