<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\ProjectBoard;
use Illuminate\Http\Request;

class ProjectBoardController extends Controller
{
    //
    public function getModules(ProjectBoard $projectBoard)
    {
        return response()->json([
            'status' => 200,
            'data' => $projectBoard->modules()->select('id', 'name', 'start_date', 'end_date', 'slug')->get()
                ->map(function ($module) use ($projectBoard) {
                    $module->public_url = authRoute('user.project-board.modules.show', ['slug' => $projectBoard->slug, 'module' => $module->slug]);
                    return $module;
                }),
        ]);
    }
}
