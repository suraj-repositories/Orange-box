<?php

namespace App\Models;

use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes, Likeable;

    protected $fillable = [
        'user_id',
        'parent_id',
        'commentable_type',
        'commentable_id',
        'message',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function totalReplies()
    {
        return $this->replies()->count();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function topLevelReplies(){
        return $this->hasMany(Comment::class, 'root_id')
                    ->whereNotNull('parent_id')
                    ->where('root_id', $this->id);
    }
    public function totalTopLevelReplies(){
        return $this->topLevelReplies()->count();
    }
}
