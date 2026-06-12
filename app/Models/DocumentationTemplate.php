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
        'preview_url',
        'is_active',
        'original_price',
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
        return asset(Storage::url($this->preview_image));
    }

    public function purchases()
    {
        return $this->hasMany(
            TemplatePurchase::class,
            'documentation_template_id'
        );
    }

    public function reviews()
    {
        return $this->hasMany(TemplateReview::class, 'documentation_template_id');
    }

    public function cartItems()
    {
        return $this->hasMany(TemplateCart::class);
    }
}
