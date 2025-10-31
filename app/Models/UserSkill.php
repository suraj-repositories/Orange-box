<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSkill extends Model
{
    use SoftDeletes;

    protected $fillable  = ['user_id', 'skill', 'level'];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

}
