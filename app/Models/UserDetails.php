<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    //

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'contact',
        'user_id',
        'deleted_at'
    ];
}
