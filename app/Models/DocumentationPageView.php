<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationPageView extends Model
{
    //
    protected $fillable = [
        'documentation_page_id',
        'session_id',
        'ip',
        'user_agent',
        'visited_at',
        'left_at',
        'duration',
    ];
}
