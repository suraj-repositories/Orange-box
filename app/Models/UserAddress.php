<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'line1',
        'line2',
        'city',
        'postal_code',
        'state',
        'country',
        'is_primary',
    ];
}
