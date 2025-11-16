<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class UserScreenLockPin extends Model
{

    //
    protected $fillable = [
        'user_id',
        'pin',
        'status'
    ];

    public function verifyPin($pin){
        return Hash::check($pin, $this->pin);
    }
}
