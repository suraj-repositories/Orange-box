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

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function icon(){
        return $this->belongsTo(Icon::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getIconUrl(){
        if(!empty($this->icon)){
            return $this->icon->getUrl();
        }else{
            return asset(config('constants.DEFAULT_FOLDER_ICON'));
        }
    }

}
