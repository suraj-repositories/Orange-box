<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SyntaxStore extends Model
{
    //

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'uuid',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function booted()
    {
        static::creating(function ($thinkPad) {
            if (empty($thinkPad->uuid)) {
                $thinkPad->uuid = (string) Str::uuid();
            }
        });
    }
}
