<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'surname', 'username', 'email', 'password', 'remember_token'
    ];

    public function system()
    {
        return $this->belongsToMany(System::class, 'system_admin', 'admin_id', 'system_id')->withTimestamps();
    }
}

