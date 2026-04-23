<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class DocumentationPageFeedback extends Model
{
    //
    use HasUuid;

    protected $table = 'documentation_page_feedback';

    protected $fillable = [
        'uuid',
        'documentation_page_id',
        'user_id',
        'rating',
        'feedback',
        'ip_address',
    ];

    public function page()
    {
        return $this->belongsTo(DocumentationPage::class, 'documentation_page_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
