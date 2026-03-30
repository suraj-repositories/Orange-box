<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class DocumentationPartner extends Model
{
    use HasUuid;
    //
    protected $fillable = [
        'uuid',
        'documentation_id',
        'name',
        'slug',
        'website_url',
        'logo_light',
        'logo_dark',
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
