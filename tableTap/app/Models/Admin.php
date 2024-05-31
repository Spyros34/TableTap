<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['name', 'email', 'password', 'active'];

  
   /**
     * The system that this admin controls.
     */
    public function system() {
        return $this->belongsTo(System::class, 'system_admin');
    }
}
