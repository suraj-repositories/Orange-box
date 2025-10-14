<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectModuleTask extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'project_module_id',
        'task_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($projectModuleTask) {
            Model::withoutEvents(function () use ($projectModuleTask) {
                if ($projectModuleTask->isForceDeleting()) {
                    $projectModuleTask->task()->withTrashed()->get()->each->forceDelete();
                } else {
                    $projectModuleTask->task()->get()->each->delete();
                }
            });
        });

        static::restoring(function ($projectModuleTask) {
            Model::withoutEvents(function () use ($projectModuleTask) {
                $projectModuleTask->task()->withTrashed()->get()->each->restore();
            });
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function module()
    {
        return $this->belongsTo(ProjectModule::class);
    }
}
