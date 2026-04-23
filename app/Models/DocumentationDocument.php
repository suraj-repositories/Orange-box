<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class DocumentationDocument extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'documentation_id',
        'release_id',
        'title',
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

    public function sponsors(){
        return $this->hasMany(DocumentationSponsor::class);
    }
}
