<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorTag extends Model
{
    //
    protected $fillable = [
        'name', 'color_code', 'emoji'
    ];
}
