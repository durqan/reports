<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users_sum_costs extends Model
{
    protected $fillable = ['user_id', 'count', 'sum'];
    protected $primaryKey = 'user_id';

    public function user ()
    {
        return $this->hasOne(users::class, 'id','user_id');
    }
}
