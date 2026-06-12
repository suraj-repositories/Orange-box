<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateCart extends Model
{
    protected $fillable = [
        'user_id',
        'documentation_template_id',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(DocumentationTemplate::class, 'documentation_template_id');
    }
}
