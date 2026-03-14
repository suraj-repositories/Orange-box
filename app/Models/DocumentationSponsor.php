<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DocumentationSponsor extends Model
{
     use SoftDeletes, HasUuid;

    protected $fillable = [
        'uuid',
        'documentation_id',
        'name',
        'website_url',
        'description',
        'logo_light',
        'logo_dark',
        'tier',
        'sort_order',
        'status',
    ];

    protected $appends = [
        'logo_light_url',
        'logo_dark_url',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function documentation()
    {
        return $this->belongsTo(Documentation::class);
    }

    public function getLogoLightUrlAttribute(): ?string
    {
        return $this->logo_light
            ? Storage::url($this->logo_light)
            : null;
    }

    public function getLogoDarkUrlAttribute(): ?string
    {
        return $this->logo_dark
            ? Storage::url($this->logo_dark)
            : null;
    }
}
