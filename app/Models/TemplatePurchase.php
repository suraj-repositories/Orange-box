<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePurchase extends Model
{
    protected $fillable = [
        'user_id',
        'documentation_template_id',
        'price',
        'transaction_id',
        'payment_status',
        'purchased_at',
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
