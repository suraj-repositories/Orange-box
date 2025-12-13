<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProjectModule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_board_id',
        'user_id',
        'name',
        'slug',
        'description',
        'project_module_type_id',
        'order',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected $appends = ['task_count', 'completed_task_count'];

    protected static function booted()
    {
        static::creating(function ($module) {
            $module->slug = self::generateUniqueSlug(
                $module->name,
                $module->user_id,
                $module->project_board_id
            );


        });

        static::updating(function ($module) {
            if ($module->isDirty('name')) {
                $module->slug = self::generateUniqueSlug(
                    $module->name,
                    $module->user_id,
                    $module->project_board_id,
                    $module->id
                );
            }
        });

        static::deleting(function ($module) {
            if ($module->isForceDeleting()) {
                $module->files()->withTrashed()->each->forceDelete();
                $module->projectModuleUsers()->withTrashed()->each->forceDelete();
                $module->projectModuleTasks()->withTrashed()->each->forceDelete();
            } else {
                $module->files()->each->delete();
                $module->projectModuleUsers()->each->delete();
                $module->projectModuleTasks()->each->delete();
            }
        });

        static::restoring(function ($module) {
            $module->files()->withTrashed()->each->restore();
            $module->projectModuleUsers()->withTrashed()->each->restore();
            $module->projectModuleTasks()->withTrashed()->each->restore();
        });
    }

    public static function generateUniqueSlug($name, $userId, $projectBoardId = null, $ignoreId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $count = 1;

        while (self::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('project_board_id', $projectBoardId)
            ->where('user_id', $userId)
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function projectBoard()
    {
        return $this->belongsTo(ProjectBoard::class);
    }


    public function projectModuleUsers()
    {
        return $this->hasMany(ProjectModuleUser::class);
    }

    public function limitedUsers()
    {
        return $this->hasMany(ProjectModuleUser::class)
            ->with('user:id,avatar,username')
            ->take(3);
    }

    public function assignees()
    {
        return $this->belongsToMany(
            User::class,
            'project_module_users',
            'project_module_id',
            'user_id'
        )->wherePivotNull('deleted_at')
            ->whereNull('users.deleted_at');
    }

    public function projectModuleTasks()
    {
        return $this->hasMany(ProjectModuleTask::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, ProjectModuleTask::class, 'project_module_id', 'id', 'id', 'task_id');
    }

    public function getTaskCountAttribute()
    {
        return $this->tasks()->count();
    }

    public function getCompletedTaskCountAttribute()
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    public function  projectModuleType()
    {
        return $this->belongsTo(ProjectModuleType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
