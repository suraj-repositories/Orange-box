<?php

namespace App\Http\Controllers\User\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request, User $user, User $owner = null)
    {
        $tasks = Task::with(['projectModuleTask', 'projectModuleTask.module', 'projectModuleTask.module.projectBoard'])

            ->where('assigned_to', $user->id)
            ->orderBy('id', 'desc')
            ->when($request->filled('module'), function ($query) use ($request) {
                $query->whereHas('projectModuleTask.module', function ($q) use ($request) {
                    $q->where('id', $request->module);
                });
            })
            ->when($request->filled('project'), function ($query) use ($request) {
                $query->whereHas('projectModuleTask.module.projectBoard', function ($q) use ($request) {
                    $q->where('id', $request->project);
                });
            })
            ->paginate();

        $filter = [];

        if ($request->filled('project')) {
            $filter['project'] = ProjectBoard::where('id', $request->project)->value('title');
        }
        if ($request->filled('module')) {
            $filter['module'] = ProjectModule::where('id', $request->project)->value('name');
        }

        return view('user.project_tracker.tasks.task_list', [
            'title' => 'Collaboration Tasks',
            'tasks' => $tasks,
            'filter' => $filter
        ]);
    }

    public function show(User $user, User $owner, Task $task, Request $request)
    {

        if ($task->assigned_to != $user->id) {
            abort(404, 'Taks Not Found!');
        }

        $task->load(['module.projectBoard', 'files', 'subTasks', 'subTasks.files', 'subTasks.user']);

        $imageFiles = $task->files->filter(function ($file) {
            return str_starts_with($file->mime_type, 'image/');
        });

        $otherFiles = $task->files->filter(function ($file) {
            return !str_starts_with($file->mime_type, 'image/');
        });

        return view('user.project_tracker.tasks.task_show', [
            'title' => $task->title,
            'task' => $task,
            'projectModule' => $task->module,
            'projectBoard' => $task->module->projectBoard,
            'imageFiles' => $imageFiles,
            'otherFiles' => $otherFiles
        ]);
    }
}
