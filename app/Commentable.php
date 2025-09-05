<?php

namespace App;

use App\Models\Comment;

trait Commentable
{
    //
    public function comments(){
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    public function topLevelComments(){
        return $this->comments()->whereNull('parent_id');
    }

    public function totalCommentsCount(){
        return $this->comments()->count();
    }

    public function topLevelCommentsCount(){
        return $this->topLevelComments()->count();
    }

    public function commentBy($userId){
        return $this->comments()->where('user_id', $userId)->exists();
    }

}
