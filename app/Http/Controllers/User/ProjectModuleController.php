<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\ProjectModuleUser;
use App\Models\User;
use Illuminate\Support\Str;
use App\Services\FileService;
use Illuminate\Http\Request;

class ProjectModuleController extends Controller
{

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    //
    public function index(User $user, $slug, Request $request)
    {

        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        return view("user.project_tracker.project_modules.project_module_list", compact('projectBoard'));
    }

    public function create(User $user, $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }
        return view("user.project_tracker.project_modules.project_module_form", compact('projectBoard'));
    }

    public function store(User $user, $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $validated = $request->validate([
            'name' => 'required|max:255|string',
            'type' => 'nullable|max:255',
            'description' => 'nullable|max:3000|string',
            'user' => 'nullable|array',
            'user.*' => 'exists:users,id',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size')
        ]);


        $projectModule = new ProjectModule();
        $projectModule->project_board_id = $projectBoard->id;
        $projectModule->name = $request->input('name');
        $projectModule->slug = Str::slug($request->name);
        $projectModule->description =  $request->input('description', null);
        $projectModule->type =  $request->input('type', null);
        $projectModule->save();

        if ($request->has('media_files')) {
            $media = $request->media_files;
            if (is_array($media)) {
                foreach ($media as $file) {
                    $projectModule->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'daily_digests'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                        'file_size' => $file->getSize() ?? null,
                        'user_id' => $user->id
                    ]);
                }
            } else {
                $projectModule->files()->create([
                    'file_path' => $this->fileService->uploadFile($media, 'daily_digests'),
                    'file_name' => $this->fileService->getFileName($media),
                    'mime_type' => $this->fileService->getMimeType($media),
                    'file_size' => $media->getSize() ?? null,
                    'user_id' => $user->id
                ]);
            }
        }

        if ($request->has('user')) {

            foreach ($request->user as $user) {
                $projectModule->projectModuleUsers()->create([
                    'project_module_id' => $projectModule->id,
                    'user_id' => $user,
                ]);
            }
        }


        return redirect()->back()->with('success', 'Module Created Successfully!');
    }
}
