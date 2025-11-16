<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class UserMasterKey extends Model
{
    //
    protected $fillable = [
        'user_id',
        'master_key',
        'status'
    ];

    public function verifyMasterKey($key){
        return Hash::check($key, $this->master_key);
    }

}
