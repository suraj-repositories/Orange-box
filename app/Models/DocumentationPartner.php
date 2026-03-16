<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class DocumentationPartner extends Model
{
    //
    protected $fillable = [
        'documentation_id',
        'name',
        'slug',
        'website_url',
        'logo',
        'banner',
        'location',
        'latitude',
        'longitude',
        'short_description',
        'description',
        'sort_order',
        'is_spotlight_partner',
        'status',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'sort_order' => 'integer',
    ];

    public function documentation(){
        return $this->belongsTo(Documentation::class);
    }
}
