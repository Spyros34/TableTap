<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kitchen extends Authenticatable
{
    protected $fillable = ['name', 'password'];

    /**
     * Define the many-to-many relationship with Shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_kitchen');
    }
}