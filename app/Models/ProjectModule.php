<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectModule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_board_id',
        'name',
        'slug',
        'description',
        'type',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

     public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function projectModuleUsers(){
        return $this->hasMany(ProjectModuleUser::class);
    }

}
