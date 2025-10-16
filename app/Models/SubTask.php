<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTask extends Model
{
    //
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'user_id',
        'task_id',
        'description',
        'status',
        'uuid'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    protected static function booted()
    {

        static::deleting(function ($subTask) {
            Model::withoutEvents(function () use ($subTask) {
                if ($subTask->isForceDeleting()) {
                    $subTask->files()->withTrashed()->get()->each->forceDelete();
                } else {
                    $subTask->files()->get()->each->delete();
                }
            });
        });

        static::restoring(function ($subTask) {
            Model::withoutEvents(function () use ($subTask) {
                $subTask->files()->withTrashed()->get()->each->restore();
            });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->files->filter(function ($file) {
            return str_starts_with($file->mime_type, 'image/');
        });
    }

    public function otherFiles()
    {
        return $this->files->filter(function ($file) {
            return !str_starts_with($file->mime_type, 'image/');
        });;
    }
}
