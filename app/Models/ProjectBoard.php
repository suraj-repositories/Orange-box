<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProjectBoard extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'status',
        'start_date',
        'end_date',
        'user_id',
        'progress',
        'thumbnail',
        'color_tag',
    ];

    protected $appends = [
        'thumbnail_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($project) {
            $project->slug = self::generateUniqueSlug($project->title, $project->user_id);
        });

        static::updating(function ($project) {
            if ($project->isDirty('title')) {
                $project->slug = self::generateUniqueSlug($project->title, $project->user_id, $project->id);
            }
        });
    }

    public static function generateUniqueSlug($title, $userId, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('user_id', $userId)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/default-thumbnail.png');
    }
}
