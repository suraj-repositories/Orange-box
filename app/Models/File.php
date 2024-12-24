<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    //

    protected $fillable = [
        'file_path',
        'file_name',
        'mime_type'
    ];

    public function imageable(){
        return $this->morphTo();
    }

    public function getFileUrl(){
        if(Storage::disk('public')->exists($this->file_path)){
            return url('storage/'.$this->file_path);
        }
        return config('constants')['DEFAULT_DIGESTION_SHOW_IMAGE'];
    }

}
