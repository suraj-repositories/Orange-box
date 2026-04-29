<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class DocumentationTemplate extends Model
{
    //
    use HasUuid;

    protected $fillable = [
        'uuid',
        'title',
        'key',
        'description',
        'components',
        'config',
        'preview_image',
        'is_active',
        'sort_order',
    ];
    protected $casts = [
        'components' => 'array',
        'config' => 'array',
        'is_active' => 'boolean',
    ];
}
