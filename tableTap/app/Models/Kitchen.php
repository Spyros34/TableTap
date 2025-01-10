<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kitchen extends Authenticatable
{
    protected $fillable = ['name', 'password', 'shop_id'];

    /**
     * Define the many-to-many relationship with Shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */   
    public function shopRelation()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}