<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationSocialLink extends Model
{
    //
    protected $fillable = [
        'documentation_id',
        'social_media_platform_id',
        'url',
        'icon',
        'sort_order',
        'status',
    ];

    public function platform(){
        return $this->belongsTo(SocialMediaPlatform::class, 'social_media_platform_id');
    }
}
