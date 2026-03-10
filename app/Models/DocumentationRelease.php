<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationRelease extends Model
{
    protected $fillable = [
        'documentation_id',
        'title',
        'version',
        'is_current',
        'is_published',
        'released_at',
    ];

    protected $casts = [
        'released_at' => 'datetime'
    ];

    protected static function booted()
    {
        static::deleting(function ($release) {
            DocumentationPage::where('release_id', $release->id)->delete();
            DocumentationDocument::where('release_id', $release->id)->delete();
        });
    }



    public function documentation()
    {
        return $this->belongsTo(Documentation::class);
    }

     public function pages()
    {
        return $this->hasMany(DocumentationPage::class, 'release_id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentationDocument::class, 'release_id');
    }
}
