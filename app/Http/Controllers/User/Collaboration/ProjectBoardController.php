<?php

namespace App\Http\Controllers\User\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\ProjectBoard;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectBoardController extends Controller
{
    //
    public function index(User $user, User $owner = null)
    {
        $projectBoards = ProjectBoard::with(['colorTag', 'users'])
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('user.project_tracker.project_board.project_board_list', [
            'title' => 'Collaboration Projects',
            'projectBoards' => $projectBoards,
        ]);
    }

    public function show(User $user, User $owner, $slug, Request $request)
    {
        $projectBoard = ProjectBoard::with(['colorTag', 'users', 'modules.assignees'])
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->where('slug', $slug)->first();

        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $query =  $projectBoard->modules()
            ->whereHas('assignees', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->withCount('projectModuleUsers')
            ->with('limitedUsers');

        $projectModules = $query->orderBy('id', 'desc')->paginate();
        $tasks = $projectBoard->tasks()->where('assigned_to', $user->id)->latest()->take(10)->paginate();

        return view('user.project_tracker.project_board.project_board_show', [
            'title' => 'Collaboration Project',
            'projectBoard' => $projectBoard,
            'projectModules' => $projectModules,
            'tasks' => $tasks
        ]);
    }
}
