<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetails extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'contact',
        'user_id',
        'deleted_at'
    ];
}
