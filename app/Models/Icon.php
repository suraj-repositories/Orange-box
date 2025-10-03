<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    //
    protected $fillable = [
        'name',
        'category',
        'file_path',
        'order',
        'status'
    ];

    public function getUrl(){
        return asset($this->file_path);
    }

}
