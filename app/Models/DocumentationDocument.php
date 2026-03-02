<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationDocument extends Model
{
    //

    protected $fillable = [
        'documentation_id',
        'release_id',
        'title',
        'slug',
        'type',
        'content',
        'status',
    ];
}
