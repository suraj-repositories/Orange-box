<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Likeable;
use App\Traits\Viewable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SyntaxStore extends Model
{
    //
    use SoftDeletes, Likeable, Commentable, Viewable;

    protected $fillable = [
        'user_id',
        'title',
        'preview_text',
        'content',
        'status',
        'uuid',
        'file_id',
        'emoji_id'
    ];

    protected $appends = [
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

    public function emoji()
    {
        return $this->belongsTo(Emoji::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function getFileUrlAttribute()
    {
        if ($this->file) {
            return $this->file->getFileUrl();
        }
        return null;
    }

    public function getVisitUrlAttribute()
    {
        return authRoute('user.syntax-store.show', ['syntaxStore' => $this]);
    }
}
