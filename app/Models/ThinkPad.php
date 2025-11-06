<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ThinkPad extends Model
{
    use SoftDeletes, Likeable, Commentable;
    //
    protected $fillable = [
        'user_id',
        'title',
        'sub_title',
        'description',
        'emoji_id',
        'file_id',
        'uuid',
        'status',
        'visibility'
    ];

    protected $appends = [
        'visibility_icon',
        'visit_url'
    ];


    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function booted()
    {
        static::creating(function ($thinkPad) {
            if (empty($thinkPad->uuid)) {
                $thinkPad->uuid = (string) Str::uuid();
            }
        });

        static::deleting(function ($thinkPad) {
            if ($thinkPad->isForceDeleting()) {
                $thinkPad->files()->withTrashed()->forceDelete();
                $thinkPad->picture()?->forceDelete();
            } else {
                $thinkPad->files()->delete();
                $thinkPad->picture()?->delete();
            }
        });

        static::restoring(function ($thinkPad) {
            $thinkPad->files()->withTrashed()->restore();
            $thinkPad->picture()?->restore();
        });
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


    public function getVisibilityIconAttribute()
    {
        return config('icons.visibility')[$this->visibility] ?? config('icons.visibility.private');
    }

     public function getVisitUrlAttribute(){
        return authRoute('user.think-pad.show', ['thinkPad' => $this]);
    }
}
