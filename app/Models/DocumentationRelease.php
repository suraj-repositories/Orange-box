<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationRelease extends Model
{
    protected $fillable = [
        'documentation_id',
        'version',
        'is_current',
        'is_published',
        'released_at',
    ];

    protected $casts = [
        'released_at' => 'datetime'
    ];

    public function documentation()
    {
        return $this->belongsTo(Documentation::class);
    }
}
