<?php

namespace App\Traits;

use App\Models\Comment;

trait Commentable
{
    //
    public function comments(){
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    public function totalComments(){
        return $this->morphMany(Comment::class, 'commentable')->where('commentable_id', $this->id);
    }

    public function topLevelComments(){
        return $this->comments()->whereNull('parent_id');
    }

    public function totalCommentsCount(){
        return $this->totalComments()->count();
    }

    public function topLevelCommentsCount(){
        return $this->topLevelComments()->count();
    }

    public function commentBy($userId){
        return $this->comments()->where('user_id', $userId)->exists();
    }



}
