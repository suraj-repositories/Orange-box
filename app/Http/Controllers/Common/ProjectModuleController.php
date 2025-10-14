<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\ProjectModule;
use Illuminate\Http\Request;

class ProjectModuleController extends Controller
{
    //
    public function getAssignees(ProjectModule $projectModule)
    {
        return response()->json([
            'status' => 200,
            'data' => $projectModule->assignees()->select('users.id', 'users.username', 'users.avatar')->get(),
            'module_due_date' => $projectModule->end_date
        ]);
    }
}
