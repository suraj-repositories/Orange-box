<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'file_path',
        'file_name',
        'mime_type'
    ];

    public function fileable()
    {
        return $this->morphTo();
    }


    public function getFileUrl(){
        if(Storage::disk('public')->exists($this->file_path)){
            return url('storage/'.$this->file_path);
        }
        return config('constants')['DEFAULT_DIGESTION_SHOW_IMAGE'];
    }

}
