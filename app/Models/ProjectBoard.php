<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class ProjectBoard extends Model
{
    use SoftDeletes, HasRelationships;

    protected $table = "project_boards";

    protected $fillable = [
        'title',
        'description',
        'preview_text',
        'slug',
        'status',
        'start_date',
        'end_date',
        'user_id',
        'progress',
        'thumbnail',
        'color_tag_id',
    ];

    protected $appends = ['thumbnail_url', 'public_url'];

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

        static::deleting(function ($project) {

            if ($project->isForceDeleting()) {
                $project->modules()->withTrashed()->get()->each->forceDelete();
                $project->files()->withTrashed()->get()->each->forceDelete();
            } else {
                $project->modules()->get()->each->delete();
                $project->files()->get()->each->delete();
            }
        });

        static::restoring(function ($project) {
            $project->modules()->withTrashed()->get()->each->restore();
            $project->files()->withTrashed()->get()->each->restore();
        });
    }

    public static function generateUniqueSlug($title, $userId, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (self::where('user_id', $userId)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function modules()
    {
        return $this->hasMany(ProjectModule::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function tasks()
    {
        return $this->hasManyDeep(
            Task::class,
            [ProjectModule::class, ProjectModuleTask::class],
            ['project_board_id', 'project_module_id', 'id'],
            ['id', 'id', 'task_id']
        );
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail)
            : asset('images/default-thumbnail.png');
    }

    public function getPublicUrlAttribute()
    {
        return authRoute('user.project-board.show', ['slug' => $this->slug]);
    }

    public function colorTag()
    {
        return $this->belongsTo(ColorTag::class);
    }

    public function users()
    {
        return $this->hasManyDeepFromRelations($this->modules(), (new ProjectModule)->assignees())
                ->distinct('users.id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
