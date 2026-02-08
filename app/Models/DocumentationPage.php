<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class DocumentationPage extends Model
{
    //
    use HasUuid;

    protected $fillable = [
        'user_id',
        'uuid',
        'documentation_id',
        'title',
        'type',
        'slug',
        'git_link',
        'content',
        'parent_id',
        'sort_order',
        'icon',
        'is_published',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentation()
    {
        return $this->belongsTo(documentation::class);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
