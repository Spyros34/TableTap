<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;

    protected $fillable = [
        'active'
    ];

    public function admin()
    {
        return $this->belongsToMany(Admin::class, 'system_admin', 'system_id', 'admin_id')->withTimestamps();
    }
}

