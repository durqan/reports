<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    public function costs()
    {
        return $this->hasMany(costs::class,'user_id', 'id');
    }
}
