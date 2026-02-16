<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
}
