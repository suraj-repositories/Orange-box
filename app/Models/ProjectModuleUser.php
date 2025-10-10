<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectModuleUser extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'project_module_id',
        'user_id',
        'role'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function projectModule(){
        return $this->belongsTo(ProjectModule::class);
    }

}
