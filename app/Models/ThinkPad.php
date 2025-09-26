<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThinkPad extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'sub_title',
        'description',
        'emoji_id',
        'file_id',
        'slug',
        'status',
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

}
