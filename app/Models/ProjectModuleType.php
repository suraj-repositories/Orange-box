<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectModuleType extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'status', 'color_tag_id'
    ];

    public function colorTag(){
        return $this->belongsTo(ColorTag::class);
    }

}
