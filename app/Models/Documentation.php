<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Documentation extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'url',
        'logo_light',
        'logo_sm_light',
        'logo_dark',
        'logo_sm_dark',
        'status',
    ];

    protected $appends = [
        'full_url',
        'logo_url',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullUrlAttribute(): ?string
    {
        if (!$this->user) {
            return null;
        }

        return rtrim(config('app.url'), '/')
            . '/' . $this->user->username
            . '/docs/' . $this->url;
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_light
            ? Storage::url($this->logo_light)
            : null;
    }
}
