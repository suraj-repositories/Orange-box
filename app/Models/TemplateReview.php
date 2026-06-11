<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateReview extends Model
{
    //
    protected $fillable = [
        'documentation_template_id',
        'user_id',
        'comment',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(
            DocumentationTemplate::class,
            'documentation_template_id'
        );
    }
}
