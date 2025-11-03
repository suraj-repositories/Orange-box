<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DailyDigest extends Model
{
    use SoftDeletes, Likeable, Commentable;

    protected $primaryKey = 'id';
    //
    protected $fillable = [
        'user_id',
        'title',
        'sub_title',
        'description',
        'emoji_id',
        'file_id',
        'uuid',
        'visibility'
    ];

    protected $appends = [
        'visibility_icon'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function emoji()
    {
        if (!isset($this->emoji_id)) {
            return null;
        }
        return $this->belongsTo(Emoji::class);
    }

    public function picture()
    {
        if (!isset($this->file_id)) {
            return null;
        }
        return $this->belongsTo(File::class);
    }

    /**
     * Handle model events for cascading deletes.
     */
    protected static function booted()
    {
        static::creating(function ($thinkPad) {
            if (empty($thinkPad->uuid)) {
                $thinkPad->uuid = (string) Str::uuid();
            }
        });

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


    public function getVisibilityIconAttribute(){
        return config('icons.visibility')[$this->visibility] ?? config('icons.visibility.private');
    }
}
