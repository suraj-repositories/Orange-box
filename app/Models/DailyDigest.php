<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyDigest extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    //
    protected $fillable = [
        'user_id',
        'title',
        'sub_title',
        'description',
        'emoji_id',
        'file_id'
    ];

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    public function emoji(){
        if(!isset($this->emoji_id)){
            return null;
        }
        return $this->belongsTo(Emoji::class);
    }

    public function picture(){
        if(!isset($this->file_id)){
            return null;
        }
        return $this->belongsTo(File::class);
    }

    /**
     * Handle model events for cascading deletes.
     */
    protected static function booted()
    {
        static::deleting(function ($digest) {
            if ($digest->isForceDeleting()) {
                $digest->files()->withTrashed()->forceDelete();
                $digest->picture()?->forceDelete();
            } else {
                $digest->files()->delete();
                $digest->picture()?->delete();
            }
        });

        static::restoring(function ($digest) {
            $digest->files()->withTrashed()->restore();
            $digest->picture()?->restore();
        });
    }
}
