<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppTheme extends Model
{
    //
    protected $fillable = [
        'title',
        'theme_key',
        'theme_image',
        'logo_light',
        'logo_dark',
        'logo_sm'
    ];
}
