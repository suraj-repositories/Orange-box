<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Documentation extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'logo',
        'url',
        'status'
    ];

    protected $appends = [
        'full_url',
        'logo_url'
    ];


    public function getRouteKeyName()
    {
        return 'uuid';
    }


    protected static function booted()
    {
        static::creating(function ($doc) {
            if (empty($doc->uuid)) {
                $doc->uuid = (string) Str::uuid();
            }
        });
    }

    public function getFullUrlAttribute()
    {
        $urlPrefix = rtrim(config('app.url'), '/') . '/' . $this->user->username . '/docs/';
        return $urlPrefix . $this->url;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLogoUrlAttribute(){
        return $this->logo;
    }
}
