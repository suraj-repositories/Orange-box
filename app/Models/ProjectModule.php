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

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($projectModule) {
            $projectModule->slug = self::generateUniqueSlug($projectModule, $projectModule->user_id);
        });

        static::updating(function ($projectModule) {
            if ($projectModule->isDirty('name')) {
                $projectModule->slug = self::generateUniqueSlug($projectModule, $projectModule->user_id, $projectModule->id);
            }
        });
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
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

    public static function generateUniqueSlug($module, $userId, $ignoreId = null)
    {
        $baseSlug = Str::slug($module->name);

        $slug = $baseSlug;
        $count = 1;
        $projectId = $module->project_board_id ?? null;
        $userId = $module->created_by ?? null;

        while (self::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('project_board_id', $projectId)
            ->where('user_id', $userId)
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function projectModuleTasks()
    {
        return $this->hasMany(ProjectModuleTask::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(
            Task::class,
            ProjectModuleTask::class,
            'project_module_id',
            'id',
            'id',
            'task_id'
        );
    }
}
