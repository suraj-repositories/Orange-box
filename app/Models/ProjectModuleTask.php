<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectModuleTask extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'project_module_id',
        'task_id',
    ];
}
