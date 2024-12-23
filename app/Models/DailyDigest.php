<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDigest extends Model
{
    protected $primaryKey = 'id';
    //
    protected $fillable = [
        'title',
        'sub_title',
        'description'
    ];

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

}
