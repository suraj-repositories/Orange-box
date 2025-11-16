<?php

namespace App\Models;

use App\Facades\Setting;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    //
    protected $fillable = [
        'user_id',
        'setting_id',
        'value'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function setting(){
        return $this->belongsTo(Setting::class);
    }
}
