<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ScreenLock extends Model
{
    //

    protected $fillable = [
        'user_id',
        'uuid',
        'redirect_url',
        'user_agent',
        'ip_address',
        'locked_at',
        'expires_at',
        'unlocked',
        'unlocked_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'locked_at' => 'datetime',
        'expires_at' => 'datetime',
        'unlocked_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($task) {
            if (empty($task->uuid)) $task->uuid = (string) Str::uuid();
        });
    }
}
