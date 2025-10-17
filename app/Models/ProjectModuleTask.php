<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectModuleTask extends Model
{
    use SoftDeletes;

    protected $fillable = ['project_module_id', 'task_id'];

    protected static function booted()
    {
        static::deleting(function ($pivot) {
            Model::withoutEvents(function () use ($pivot) {
                if ($pivot->isForceDeleting()) {
                    $pivot->task()->withTrashed()->get()->each->forceDelete();
                } else {
                    $pivot->task()->get()->each->delete();
                }
            });
        });

        static::restoring(function ($pivot) {
            Model::withoutEvents(function () use ($pivot) {
                $pivot->task()->withTrashed()->get()->each->restore();
            });
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function module()
    {
        return $this->belongsTo(ProjectModule::class, 'project_module_id', 'id');
    }
}
