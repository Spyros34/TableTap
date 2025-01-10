<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable; // << Important

class Waiter extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'surname', 'username', 'password'];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'waiter_order');
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_waiter')
                    ->withTimestamps();
    }
}