<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderFactory extends Model
{
    //
    protected $fillable = [
        'user_id',
        'icon_id',
        'slug',
        'name'
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function icon(){
        return $this->belongsTo(Icon::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getIconUrl(){
        if(!empty($this->icon)){
            return $this->icon->getUrl();
        }else{
            return asset(config('constants.DEFAULT_FOLDER_ICON'));
        }
    }

}
