<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class DocumentationPage extends Model
{
    //
    use HasUuid, Searchable;

    protected $fillable = [
        'user_id',
        'uuid',
        'documentation_id',
        'title',
        'type',
        'slug',
        'release_id',
        'git_link',
        'content',
        'headings',
        'h1',
        'h2',
        'content_format',
        'parent_id',
        'sort_order',
        'icon',
        'is_published',
    ];

    protected $appends = [
        'content_markdown',
        'content_html',
        'content_editorjs',
    ];

    protected $casts = [
        'headings' => 'array',
        'h2' => 'array',
        'is_published' => 'boolean',
    ];


    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'h1' => $this->h1,
            'h2' => implode(' ', $this->h2 ?? []),
            // 'h3' => $this->h3 ?? [],
            'content' => strip_tags($this->content),
        ];
    }

    public function shouldBeSearchable()
    {
        return $this->is_published && $this->type === 'file';
    }

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

    public function getContentMarkdownAttribute()
    {
        return $this->content_format == 'markdown' ? $this->content : '';
    }
    public function getContentHtmlAttribute()
    {
        if ($this->content_format === 'markdown') {
            return Str::markdown($this->content ?? '');
        }
        return $this->content_format == 'html' ? $this->content : '';
    }
    public function getContentEditorjsAttribute()
    {
        return $this->content_format == 'editorjs' ? $this->content : '';
    }

    public function getFullPathAttribute()
    {
        $segments = [];
        $page = $this;

        while ($page) {
            array_unshift($segments, $page->slug);
            $page = $page->parent;
        }

        return implode('/', $segments);
    }

    public function deleteWithChildren()
    {
        foreach ($this->children as $child) {
            $child->deleteWithChildren();
        }

        $this->delete(); // or forceDelete() if using SoftDeletes
    }

    public function documentationRelease()
    {
        return $this->belongsTo(DocumentationRelease::class, 'release_id', 'id');
    }

   public function getH2Attribute($value)
{
    if (is_array($value)) {
        return $value;
    }

    if (is_string($value)) {
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    return [];
}

public function getHeadingsAttribute($value)
{
    if (is_array($value)) {
        return $value;
    }

    if (is_string($value)) {
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    return [];
}
}
