<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSocialMediaLink extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'social_media_platform_id',
        'url'
    ];

}
