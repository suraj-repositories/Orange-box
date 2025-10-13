<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FileService;
use Illuminate\Support\Facades\Gate;

class ProjectModuleTaskController extends Controller
{
    //

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
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
        $projectModules = ProjectModule::where('user_id', $user->id)->get();

        return view('user.project_tracker.tasks.task_form', [
            'projectBoards' => $projectBoards,
            'projectModules' => $projectModules
        ]);
    }


    public function store(User $user, $slug = null, $module = null, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size'),
            'project_board_id' => 'nullable|exists:project_boards,id',
            'project_module_id' => 'nullable|exists:project_modules,id',
        ]);

        if ($slug && $module) {
            $board = ProjectBoard::where('slug', $slug)->firstOrFail();
            $module = $board->modules()->where('slug', $module)->firstOrFail();
            $validated['project_board_id'] = $board->id;
            $validated['project_module_id'] = $module->id;
        } else {
            $validated['project_board_id'] = $request->project_board_id;
            $validated['project_module_id'] = $request->project_module_id;
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

        return redirect()->back()->with('success', 'Task created successfully!');
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

    public function update(User $user, $slug, $module, Task $task, Request $request)
    {
        Gate::authorize('update', $task);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size'),
            'project_board_id' => 'nullable|exists:project_boards,id',
            'project_module_id' => 'nullable|exists:project_modules,id',
        ]);

        if ($slug && $module) {
            $board = ProjectBoard::where('slug', $slug)->firstOrFail();
            $module = $board->modules()->where('slug', $module)->firstOrFail();
            $validated['project_board_id'] = $board->id;
            $validated['project_module_id'] = $module->id;
        } else {
            $validated['project_board_id'] = $request->project_board_id;
            $validated['project_module_id'] = $request->project_module_id;
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

        if ($request->has('media_files')) {
            $media = $request->media_files;
            $files = is_array($media) ? $media : [$media];

            foreach ($files as $file) {
                $task->files()->create([
                    'file_path' => $this->fileService->uploadFile($file, 'think_pad'),
                    'file_name' => $this->fileService->getFileName($file),
                    'mime_type' => $this->fileService->getMimeType($file),
                    'file_size' => $file->getSize() ?? null,
                    'user_id' => $user->id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    public function destroy(User $user, Task $task, Request $request)
    {
        Gate::authorize('delete', $task);
        $task->delete();
        return redirect()->back()->with('success', 'Task Deleted Successfully!');
    }
}
