<?php

namespace App\Traits;

use App\Models\Like;

trait Likeable
{
    //
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like($userId){
        return $this->likes()->updateOrCreate(
            ['user_id' => $userId],
            ['value' => 1]
        );
    }

    public function dislike($userId){
        return $this->likes()->updateOrCreate(
            ['user_id' => $userId],
            ['value' => -1]
        );
    }

    public function removeLike($userId){
        return $this->likes()->where('user_id', $userId)->delete();
    }

    public function likesCount(){
        return $this->likes()->where('value', 1)->count();
    }

    public function dislikesCount(){
        return $this->likes()->where('value', -1)->count();
    }

    public function likedBy($userId){
        return $this->likes()->where('user_id', $userId)->where('value', 1)->exists();
    }

    public function dislikedBy($userId){
        return $this->likes()->where('user_id', $userId)->where('value', -1)->exists();
    }
}
