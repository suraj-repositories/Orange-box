<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationPage extends Model
{
    //

    protected $fillable = [
        'user_id',
        'documentation_id',
        'title',
        'slug',
        'git_link',
        'content',
        'parent_id',
        'sort_order',
        'icon',
        'is_published',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function documentation(){
        return $this->belongsTo(documentation::class);
    }
}
