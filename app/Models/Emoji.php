<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emoji extends Model
{
    //
    protected $table = "emojis";

    protected $fillable = ['emoji', 'name', 'emoji_category_id'];

    public function category()
    {
        return $this->belongsTo(EmojiCategory::class, 'emoji_category_id');
    }
}
