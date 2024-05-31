<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;

    protected $fillable = ['system_name', 'active'];

   
   /**
     * The admins that control this system.
     */
    public function admins() {
        return $this->belongsToMany(Admin::class, 'system_admin');
    }
}
