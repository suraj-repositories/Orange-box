<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'price',
        'sort_order',
        'documentation_template_id',
    ];
    protected $casts = [
        'components' => 'array',
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function getPreviewImageUrlAttribute()
    {
        return Storage::url($this->preview_image);
    }

    public function purchases()
    {
        return $this->hasMany(
            TemplatePurchase::class,
            'documentation_template_id'
        );
    }
}
