<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPublicKey extends Model
{
    use SoftDeletes;
    //
    protected $table = 'users_public_keys';

    protected $fillable = [
        'user_id', 'public_pem'
    ];

}
