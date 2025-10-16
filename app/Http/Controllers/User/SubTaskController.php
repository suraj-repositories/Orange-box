<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubTaskController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(User $user, Task $task, Request $request)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size'),
            'status' => 'required|in:pending,in_progress,completed,on_hold'
        ]);

        $subTask = SubTask::create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        if ($request->has('media_files')) {
            $media = $request->media_files;
            if (is_array($media)) {
                foreach ($media as $file) {
                    $subTask->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'sub_task'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                        'file_size' => $file->getSize() ?? null,
                        'user_id' => $user->id
                    ]);
                }
            } else {
                $subTask->files()->create([
                    'file_path' => $this->fileService->uploadFile($media, 'sub_task'),
                    'file_name' => $this->fileService->getFileName($media),
                    'mime_type' => $this->fileService->getMimeType($media),
                    'file_size' => $media->getSize() ?? null,
                    'user_id' => $user->id
                ]);
            }
        }

        return redirect()->back()->with('success', 'Sub-Task created successfully!');
    }

    public function update(User $user, SubTask $subTask , Request $request){
         $validated = $request->validate([
            'description' => 'nullable|string',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size'),
            'status' => 'required|in:pending,in_progress,completed,on_hold'
        ]);

        $subTask->description = $validated['description'] ?? null;
        $subTask->status = $validated['status'] ?? null;
        $subTask->updated_at = now();
        $subTask->save();

        if ($request->has('media_files')) {
            $media = $request->media_files;
            if (is_array($media)) {
                foreach ($media as $file) {
                    $subTask->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'sub_task'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                        'file_size' => $file->getSize() ?? null,
                        'user_id' => $user->id
                    ]);
                }
            } else {
                $subTask->files()->create([
                    'file_path' => $this->fileService->uploadFile($media, 'sub_task'),
                    'file_name' => $this->fileService->getFileName($media),
                    'mime_type' => $this->fileService->getMimeType($media),
                    'file_size' => $media->getSize() ?? null,
                    'user_id' => $user->id
                ]);
            }
        }
        return redirect()->back()->with('success', 'Sub-Task Updated Successfully!');
    }

    public function destroy(User $user, SubTask $subTask, Request $request)
    {
        Gate::authorize('delete', $subTask);
        $subTask->delete();

        return redirect()->back()->with('success', 'Sub-Task Deleted Successfully!');
    }
}
