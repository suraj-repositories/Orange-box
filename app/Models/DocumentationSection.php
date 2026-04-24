<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class DocumentationSection extends Model
{
    use Searchable;

    protected $fillable = [
        'documentation_page_id',
        'heading',
        'slug',
        'level',
        'content',
        'position',
    ];

    public function page()
    {
        return $this->belongsTo(DocumentationPage::class, 'documentation_page_id');
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'heading' => $this->heading,
            'content' => strip_tags($this->content),
            'page_id' => $this->documentation_page_id,
        ];
    }
}
