<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationDocument extends Model
{
    protected $fillable = [
        'documentation_id',
        'release_id',
        'title',
        'slug',
        'type',
        'content',
        'description',
        'status',
    ];

    public function documentation()
    {
        return $this->belongsTo(Documentation::class);
    }

    public function release()
    {
        return $this->belongsTo(DocumentationRelease::class, 'release_id', 'id');
    }
}
