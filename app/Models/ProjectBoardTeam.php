<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectBoardTeam extends Model
{
    //
    protected $fillable = [
        'project_board_id',
        'user_id',
        'role',
        'is_accepted',
    ];

}
