<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    //

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function index(User $user, Request $request)
    {
        $tasks = Task::with(['projectModuleTask', 'projectModuleTask.module', 'projectModuleTask.module.projectBoard'])
            ->where('user_id', $user->id)
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
            'title' => 'Tasks',
            'tasks' => $tasks,
        ]);
    }

    public function createNested(User $user, $slug, $module, Request $request)
    {
        $projectBoard = ProjectBoard::with(['modules.projectModuleUsers.user'])
            ->where('user_id', $user->id)
            ->where('slug', $slug)
            ->first();

        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $projectModule = $projectBoard->modules->firstWhere('slug', $module);

        if (!$projectModule) {
            abort(404, "Module Not Found!");
        }
        return view('user.project_tracker.tasks.task_form', [
            'projectBoard' => $projectBoard,
            'projectModule' => $projectModule,
        ]);
    }

    public function createGlobal(User $user, Request $request)
    {
        $projectBoards = ProjectBoard::where('user_id', $user->id)->get();

        return view('user.project_tracker.tasks.task_form', [
            'projectBoards' => $projectBoards
        ]);
    }


    public function store(Request $request, User $user, $slug = null, $module = null)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size'),
            'project_board' => 'nullable|exists:project_boards,id',
            'project_module' => 'nullable|exists:project_modules,id',
        ]);

        if ($slug && $module) {
            $board = ProjectBoard::where('slug', $slug)->firstOrFail();
            $module = $board->modules()->where('slug', $module)->firstOrFail();
            $validated['project_board_id'] = $board->id;
            $validated['project_module_id'] = $module->id;
        } else {
            $validated['project_board_id'] = $request->project_board;
            $validated['project_module_id'] = $request->project_module;
        }

        $task = Task::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'due_date' => $validated['due_date'] ?? null
        ]);

        $task->projectModuleTask()->create([
            'project_module_id' => $validated['project_module_id'],
        ]);

        if ($request->has('media_files')) {
            $media = $request->media_files;
            if (is_array($media)) {
                foreach ($media as $file) {
                    $task->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'think_pad'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                        'file_size' => $file->getSize() ?? null,
                        'user_id' => $user->id
                    ]);
                }
            } else {
                $task->files()->create([
                    'file_path' => $this->fileService->uploadFile($media, 'think_pad'),
                    'file_name' => $this->fileService->getFileName($media),
                    'mime_type' => $this->fileService->getMimeType($media),
                    'file_size' => $media->getSize() ?? null,
                    'user_id' => $user->id
                ]);
            }
        }

        return redirect()->to(authRoute('user.tasks.show', ['task' => $task]))->with('success', 'Task created successfully!');
    }
    public function show(User $user, Task $task, Request $request)
    {
        if ($task->user_id != $user->id) {
            abort(404, 'Task Not Found!');
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
    public function editNested(User $user, $slug, $module, Task $task, Request $request)
    {
        $projectBoard = ProjectBoard::with(['modules.projectModuleUsers.user'])
            ->where('user_id', $user->id)
            ->where('slug', $slug)
            ->first();

        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $projectModule = $projectBoard->modules->firstWhere('slug', $module);

        if (!$projectModule) {
            abort(404, "Module Not Found!");
        }

        $media = $this->fileService->getMediaMetadata($task->files);
        return view('user.project_tracker.tasks.task_form', [
            'projectBoard' => $projectBoard,
            'projectModule' => $projectModule,
            'task' => $task,
            'media' => $media
        ]);
    }

    public function editGlobal(User $user, Task $task, Request $request)
    {
        $projectBoards = ProjectBoard::where('user_id', $user->id)->get();

        $projectModule = $task->module;
        $projectBoard = $projectModule->projectBoard;



        $media = $this->fileService->getMediaMetadata($task->files);
        return view('user.project_tracker.tasks.task_form', [
            'projectBoards' => $projectBoards,
            'projectBoard' => $projectBoard,
            'projectModule' => $projectModule,
            'task' => $task,
            'media' => $media
        ]);
    }
    public function update(UpdateTaskRequest $request, User $user, Task $task)
    {
        return $this->handleTaskUpdate($request, $user, $task);
    }
    public function updateNested(UpdateTaskRequest $request, User $user, $slug, $moduleSlug, Task $task)
    {
        return $this->handleTaskUpdate($request, $user, $task, $slug, $moduleSlug);
    }

    protected function handleTaskUpdate(UpdateTaskRequest $request, User $user, Task $task, $slug = null, $moduleSlug = null)
    {
        Gate::authorize('update', $task);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $task, $user, $slug, $moduleSlug) {
            if ($slug && $moduleSlug) {
                $board = ProjectBoard::where('slug', $slug)->firstOrFail();
                $module = $board->modules()->where('slug', $moduleSlug)->firstOrFail();
                $validated['project_board_id'] = $board->id;
                $validated['project_module_id'] = $module->id;
            } else {
                $validated['project_board_id'] = $validated['project_board'] ?? null;
                $validated['project_module_id'] = $validated['project_module'] ?? null;
            }

            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'priority' => $validated['priority'],
                'assigned_to' => $validated['assigned_to'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
            ]);

            $task->projectModuleTask()->updateOrCreate(
                ['task_id' => $task->id],
                ['project_module_id' => $validated['project_module_id']]
            );

            if (!empty($validated['media_files'])) {
                foreach ($validated['media_files'] as $file) {
                    $task->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'think_pad'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                        'file_size' => $file->getSize() ?? null,
                        'user_id' => $user->id,
                    ]);
                }
            }
        });

        return redirect()
            ->to(authRoute('user.tasks.show', ['task' => $task]))
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(User $user, Task $task, Request $request)
    {
        Gate::authorize('delete', $task);
        $task->delete();
        return redirect()->back()->with('success', 'Task Deleted Successfully!');
    }
}
