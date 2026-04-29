<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickLink extends Model
{
    //

    protected $fillable = [
        'title',
        'icon',
        'color',
        'route_name',
        'route_params',
        'external_url',
        'target',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'route_params' => 'array',
        'is_active' => 'boolean',
    ];

    public function getUrlAttribute()
    {
        if ($this->route_name) {
            return route(
                $this->route_name,
                $this->route_params ?? []
            );
        }

        return $this->external_url;
    }
}
