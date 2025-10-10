<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\ProjectModuleType;
use App\Models\ProjectModuleUser;
use App\Models\User;
use Illuminate\Support\Str;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

        $types = ProjectModuleType::select('id', 'name')->get();

        return view("user.project_tracker.project_modules.project_module_form", compact('projectBoard', 'types'));
    }

    public function store(User $user, $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        try {
            $validated = $request->validate([
                'name' => 'required|max:255|string',
                'type' => 'required|exists:project_module_types,id',
                'description' => 'nullable|max:3000|string',
                'user' => 'nullable|array',
                'user.*' => 'exists:users,id',
                'media_files' => 'nullable|array',
                'media_files.*' => 'file|max:' . config('validation_rules.max_file_size'),
                'start_date' => [
                    'nullable',
                    'date',
                    function ($_, $value, $fail) use ($request) {
                        if ($request->filled('end_date') && $value > $request->end_date) {
                            $fail('The start date must be before or equal to the end date.');
                        }
                    },
                ],
                'end_date' => [
                    'nullable',
                    'date',
                    function ($_, $value, $fail) use ($request) {
                        if ($request->filled('start_date') && $value < $request->start_date) {
                            $fail('The end date must be after or equal to the start date.');
                        }
                    },
                ],
            ]);
            $projectModule = new ProjectModule();
            $projectModule->project_board_id = $projectBoard->id;
            $projectModule->name = $validated['name'];
            $projectModule->user_id = $user->id;
            $projectModule->description = $validated['description'] ?? null;
            $projectModule->project_module_type_id = $validated['type'] ?? null;
            $projectModule->start_date = $validated['start_date'] ?? null;
            $projectModule->end_date = $validated['end_date'] ?? null;
            $projectModule->save();

            if (!empty($validated['media_files'])) {
                foreach ($validated['media_files'] as $file) {
                    $projectModule->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'daily_digests'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                        'file_size' => $file->getSize() ?? null,
                        'user_id' => $user->id
                    ]);
                }
            }

            if (!empty($validated['user'])) {
                foreach ($validated['user'] as $userId) {
                    $projectModule->projectModuleUsers()->create([
                        'project_module_id' => $projectModule->id,
                        'user_id' => $userId,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Module Created Successfully!');
        } catch (ValidationException $e) {
            $selectedUsers = collect();
            if ($request->filled('user')) {
                $selectedUsers = User::whereIn('id', $request->user)
                    ->select('id', 'username')
                    ->get()
                    ->map(function ($user) {
                        $user->avatar = $user->profilePicture();
                        return $user;
                    });
            }

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(['users' => $selectedUsers]);
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function show(User $user, $slug, $module, Request $request){


       $projectBoard = ProjectBoard::where('user_id', $user->id)
                                ->where('slug', $slug)
                                ->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $projectModule = $projectBoard->modules->where('slug', $module)->first();
        if(!$projectModule){
            abort(404, "Module Not Found!");
        }

        return view('user.project_tracker.project_modules.project_module_show', compact('projectModule', 'projectBoard'));

    }

}
