<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectModule;
use Illuminate\Http\Request;

class ProjectModuleController extends Controller
{
    //
    public function getAssignees(ProjectModule $projectModule){
        return response()->json([
            'status' => 200,
            'data' => $projectModule->assignees->select('id', 'name', 'avatar')->get()
        ]);
    }
}
