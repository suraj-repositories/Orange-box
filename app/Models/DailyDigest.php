<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDigest extends Model
{
    //
    protected $fillable = [
        'title',
        'sub-title',
        'content'
    ];
}
