<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMediaPlatform extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'uri',
        'icon',
        'color',
        'bg_color',
    ];
}
