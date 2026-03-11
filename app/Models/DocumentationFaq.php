<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationFaq extends Model
{
    //
    protected $fillable = [
        'documentation_id',
        'release_id',
        'question',
        'answer',
        'position',
        'is_active'
    ];


}
