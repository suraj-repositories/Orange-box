<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectBoard;
use Illuminate\Http\Request;

class ProjectBoardController extends Controller
{
    //
    public function getModules(ProjectBoard $projectBoard){
        return response()->json([
            'status' => 200,
            'data' => $projectBoard->modules()->select('id', 'name','start_date', 'end_date')->get(),
        ]);
    }
}
