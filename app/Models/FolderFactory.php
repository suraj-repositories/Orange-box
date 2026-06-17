<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FolderFactory extends Model
{
    use SoftDeletes;

    //
    protected $fillable = [
        'user_id',
        'icon_id',
        'slug',
        'parent_id',
        'name'
    ];

    protected static function booted()
    {
        static::deleting(function ($digest) {
            if ($digest->isForceDeleting()) {
                $digest->files()->withTrashed()->forceDelete();
            } else {
                $digest->files()->delete();
            }
        });

        static::restoring(function ($digest) {
            $digest->files()->withTrashed()->restore();
        });
    }

    public function parent()
    {
        return $this->belongsTo(FolderFactory::class, 'parent_id', 'id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function directChildFolders()
    {
        return $this->hasMany(FolderFactory::class, 'parent_id', 'id');
    }

    public function icon()
    {
        return $this->belongsTo(Icon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIconUrl()
    {
        if (!empty($this->icon)) {
            return $this->icon->getUrl();
        } else {
            return asset(config('constants.DEFAULT_FOLDER_ICON'));
        }
    }
}
