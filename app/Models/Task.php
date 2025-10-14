<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Task extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'completed_by',
        'start_date',
        'due_date',
        'completed_at',
        'uuid',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }
    protected static function booted()
    {
        static::creating(function ($task) {
            if (empty($task->uuid)) {
                $task->uuid = (string) Str::uuid();
            }
        });

        static::deleting(function ($task) {
            if ($task->isForceDeleting()) {
                Model::withoutEvents(function () use ($task) {
                    $task->files()->withTrashed()->get()->each->forceDelete();
                    $task->projectModuleTask()->withTrashed()->forceDelete();
                });
            } else {
                Model::withoutEvents(function () use ($task) {
                    $task->files()->get()->each->delete();
                    $task->projectModuleTask()->delete();
                });
            }
        });

        static::restoring(function ($task) {
            Model::withoutEvents(function () use ($task) {
                $task->files()->withTrashed()->get()->each->restore();
                $task->projectModuleTask()->withTrashed()->restore();
            });
        });
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function projectModuleTask()
    {
        return $this->hasOne(ProjectModuleTask::class);
    }

    public function module()
    {
        return $this->hasOneThrough(
            ProjectModule::class,
            ProjectModuleTask::class,
            'task_id',
            'id',
            'id',
            'project_module_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
}
