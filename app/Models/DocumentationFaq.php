<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationFaq extends Model
{
    //
    protected $fillable = [
        'documentation_document_id',
        'question',
        'answer',
        'position',
        'is_active'
    ];

    public function document(){
        return $this->belongsTo(DocumentationDocument::class, 'documentation_document_id', 'id');
    }


}
