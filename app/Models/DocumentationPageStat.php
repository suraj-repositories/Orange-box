<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationPageStat extends Model
{
    //
    protected $fillable = [
        'documentation_page_id',
        'date',
        'views',
        'unique_visitors',
        'bounces',
        'avg_time_on_page',
    ];

    public function page(){
        return $this->belongsTo(DocumentationPage::class, 'documentation_page_id', 'id');
    }
}
