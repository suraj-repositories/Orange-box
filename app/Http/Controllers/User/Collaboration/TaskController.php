<?php

namespace App\Http\Controllers\User\Collaboration;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(User $user, Request $request)
    {
        $tasks = Task::with(['projectModuleTask', 'projectModuleTask.module', 'projectModuleTask.module.projectBoard'])
            ->where('assigned_to', $user->id)
            ->orderBy('id', 'desc')
            ->when($request->filled('module'), function ($query) use ($request) {
                $query->whereHas('projectModuleTask.module', function ($q) use ($request) {
                    $q->where('slug', $request->module);
                });
            })
            ->when($request->filled('project'), function ($query) use ($request) {
                $query->whereHas('projectModuleTask.module.projectBoard', function ($q) use ($request) {
                    $q->where('slug', $request->project);
                });
            })
            ->paginate();

        return view('user.project_tracker.tasks.task_list', [
            'title' => 'Collaboration Tasks',
            'tasks' => $tasks,
        ]);
    }

    public function show(User $user, Task $task, Request $request)
    {

        if($task->assigned_to != $user->id){
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
