<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class UserKey extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'master_key',
        'screen_lock_pin',
    ];

    public function verifyMasterKey($key){
        return Hash::check($key, $this->master_key);
    }

    public function verifyScreenLockPin($pin){
        return Hash::check($pin, $this->screen_lock_pin);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
