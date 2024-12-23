<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmojiCategory extends Model
{
    //
    protected $fillable = ['name'];

    public function emojis(){
        return $this->hasMany(Emoji::class, 'emoji_category_id');
    }
}
