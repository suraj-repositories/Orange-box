<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordLocker extends Model
{
    use SoftDeletes;

    //
      protected $fillable = [
        'user_id',
        'username',
        'password',
        'url',
        'expires_at',
        'notes',
    ];

     protected $hidden = [
        'password',
        'user_id',
        'notes',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'domain'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function getDomainAttribute(){
        return parse_url($this->url, PHP_URL_HOST);
    }

}
