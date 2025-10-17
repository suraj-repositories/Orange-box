<?php

namespace App\Http\Controllers\User\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectModuleController extends Controller
{
    //

    public function index(Request $request, User $user, $slug = null)
    {
        $projectBoard = null;
        if (!empty($slug)) {
            $projectBoard = ProjectBoard::with(['users', 'modules.assignees'])
                ->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->where('slug', $slug)
                ->first();
            if (!$projectBoard) {
                abort(404, "Project Not Found!");
            }

            $query = $projectBoard->modules()
                ->withCount('projectModuleUsers')
                ->whereHas('assignees', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->with('limitedUsers');
        } else {
            $query = ProjectModule::withCount('projectModuleUsers', 'assignees', 'projectBoard')
                ->whereHas('assignees', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->whereHas('projectBoard', function ($q) use ($request) {
                    if ($request->filled('project')) {
                        $q->where('project_boards.slug', $request->project);
                    }
                })
                ->with('limitedUsers');
        }

        $projectModules = $query->orderBy('id', 'desc')->paginate();

        return view("user.project_tracker.project_modules.project_module_list", [
            'title' => 'Collaboration Project Modules',
            'projectModules' => $projectModules,
            'projectBoard' => $projectBoard,
        ]);
    }

    public function show(User $user, $slug, $module, Request $request)
    {
        $projectBoard = ProjectBoard::with(['modules.files', 'modules.projectModuleType', 'modules.projectModuleType.colorTag', 'modules.assignees'])
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->where('slug', $slug)
            ->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $projectModule = $projectBoard->modules()
            ->whereHas('assignees', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->where('slug', $module)->first();
        if (!$projectModule) {
            abort(404, "Module Not Found!");
        }

        $imageFiles = $projectModule->files->filter(function ($file) {
            return str_starts_with($file->mime_type, 'image/');
        });

        $otherFiles = $projectModule->files->filter(function ($file) {
            return !str_starts_with($file->mime_type, 'image/');
        });

        $tasks = $projectModule->tasks()->where('assigned_to', $user->id)->paginate(10);

        return view(
            'user.project_tracker.project_modules.project_module_show',
            [
                'title'         => $projectModule->name,
                'projectModule' => $projectModule,
                'projectBoard'  => $projectBoard,
                'imageFiles'    => $imageFiles,
                'otherFiles'    => $otherFiles,
                'tasks'         => $tasks,
            ]
        );
    }
}
