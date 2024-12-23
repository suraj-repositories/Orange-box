<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
