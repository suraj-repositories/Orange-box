<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectModuleRequest;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\ProjectModuleType;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ProjectModuleController extends Controller
{

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    //
    public function index(Request $request, User $user, $slug = null)
    {

        $projectBoard = null;
        if (!empty($slug)) {
            $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();
            if (!$projectBoard) {
                abort(404, "Project Not Found!");
            }
        }

        return view("user.project_tracker.project_modules.project_module_list", compact('projectBoard'));
    }

    public function createNested(User $user, $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $types = ProjectModuleType::select('id', 'name')->get();

        return view("user.project_tracker.project_modules.project_module_form", compact('projectBoard', 'types'));
    }

    public function create(User $user, Request $request)
    {
        $projectBoards = ProjectBoard::where('user_id', $user->id)->select('id', 'title', 'thumbnail')->get();
        $types = ProjectModuleType::select('id', 'name')->get();
        return view("user.project_tracker.project_modules.project_module_form", compact('projectBoards', 'types'));
    }

    public function store(Request $request, User $user, $slug = null)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|max:255|string',
                'type' => 'required|exists:project_module_types,id',
                'description' => 'nullable|max:3000|string',
                'user' => 'nullable|array',
                'user.*' => 'exists:users,id',
                'media_files' => 'nullable|array',
                'media_files.*' => 'file|max:' . config('validation_rules.max_file_size'),
                'project_board' => 'nullable|exists:project_boards,id',
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

            $projectBoard = null;
            if (!empty($slug)) {
                $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();
                if (!$projectBoard) {
                    return redirect()->back()->with(['error' => 'Project Not Exists!']);
                }
            } else {
                $projectBoard = ProjectBoard::where('user_id', $user->id)->where('id', $validated['project_board'])->first();
                if (!$projectBoard) {
                    return back()->withInput()->withErrors(['project_board' => 'Project board is required!']);
                }
            }

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
                    ->select('id', 'username', 'avatar')
                    ->get();
            }

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with(['users' => $selectedUsers]);
        } catch (\Throwable $e) {
            return redirect()->back()
                ->with(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function show(User $user, $slug, $module, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)
            ->where('slug', $slug)
            ->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $projectModule = $projectBoard->modules->where('slug', $module)->first();
        if (!$projectModule) {
            abort(404, "Module Not Found!");
        }

        return view('user.project_tracker.project_modules.project_module_show', compact('projectModule', 'projectBoard'));
    }

    public function editNested(User $user, $slug, $module, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)
            ->where('slug', $slug)
            ->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $projectModule = $projectBoard->modules->where('slug', $module)->first();
        if (!$projectModule) {
            abort(404, "Module Not Found!");
        }

        $types = ProjectModuleType::select('id', 'name')->get();
        $media = $this->fileService->getMediaMetadata($projectModule->files);
        return view("user.project_tracker.project_modules.project_module_form", compact('projectBoard', 'types', 'projectModule', 'media'));
    }

    public function edit(User $user, $module, Request $request)
    {
        $projectModule = ProjectModule::where('slug', $module)->first();
        if (!$projectModule) {
            abort(404, "Module Not Found!");
        }

        $projectBoards = ProjectBoard::where('user_id', $user->id)->select('id', 'title', 'thumbnail')->get();
        $types = ProjectModuleType::select('id', 'name')->get();
        $media = $this->fileService->getMediaMetadata($projectModule->files);
        return view("user.project_tracker.project_modules.project_module_form", compact('projectBoards', 'types', 'projectModule', 'media'));
    }

    public function update(UpdateProjectModuleRequest $request, User $user, string $slug, string $moduleSlug)
    {
        try {
            $validated = $request->validated();

            $module = $this->updateProjectModule($validated, $user, $slug, $moduleSlug);

            return redirect()
                ->to(authRoute('user.project-board.modules.show', [
                    'slug' => $module->projectBoard->slug,
                    'module' => $module->slug
                ]))
                ->with('success', 'Module Updated Successfully!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updateGlobal(UpdateProjectModuleRequest $request, User $user, string $moduleSlug)
    {
        try {
            $validated = $request->validated();

            $module = ProjectModule::with('projectBoard')
                ->where('user_id', $user->id)
                ->where('slug', $moduleSlug)
                ->firstOrFail();

            $module = $this->updateProjectModule($validated, $user, $module->projectBoard->slug, $moduleSlug);

            return redirect()
                ->to(authRoute('user.project-board.modules.show', [
                    'slug' => $module->projectBoard->slug,
                    'module' => $module->slug
                ]))
                ->with('success', 'Module Updated Successfully!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function updateProjectModule(array $validated, User $user, ?string $slug, string $moduleSlug)
    {
        $projectBoard = $slug
            ? ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first()
            : ProjectBoard::where('user_id', $user->id)->where('id', $validated['project_board'] ?? 0)->first();

        if (!$projectBoard) {
            throw new \Exception('Project board not found or not accessible.');
        }

        $module = ProjectModule::where('project_board_id', $projectBoard->id)
            ->where('slug', $moduleSlug)
            ->firstOrFail();

        DB::transaction(function () use ($module, $validated, $user) {

            $module->fill([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'project_module_type_id' => $validated['type'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
            ])->save();

            if (!empty($validated['media_files'])) {
                foreach ($validated['media_files'] as $file) {
                    $module->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'daily_digests'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                        'file_size' => $file->getSize() ?? null,
                        'user_id' => $user->id,
                    ]);
                }
            }

            $incomingUserIds = array_values(array_unique(array_map('intval', $validated['user'] ?? [])));
            $currentUserIds = $module->projectModuleUsers()->pluck('user_id')->map(fn($v) => (int) $v)->toArray();

            $toAttach = array_diff($incomingUserIds, $currentUserIds);
            $toDetach = array_diff($currentUserIds, $incomingUserIds);

            if ($toDetach) {
                $module->projectModuleUsers()->whereIn('user_id', $toDetach)->delete();
            }

            if ($toAttach) {
                $now = now();
                $rows = array_map(fn($id) => [
                    'project_module_id' => $module->id,
                    'user_id' => $id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $toAttach);

                DB::table('project_module_users')->insertOrIgnore($rows);
            }
        });

        return $module;
    }


    public function destroy(User $user, $slug, $module, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)
            ->where('slug', $slug)
            ->first();
        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $projectModule = $projectBoard->modules->where('slug', $module)->first();
        if (!$projectModule) {
            abort(404, "Module Not Found!");
        }

        Gate::authorize('delete', $projectModule);

        $projectModule->delete();

        return redirect()->to(authRoute('user.project-board.show', ['slug' => $slug]))->with('success', 'Module deleted successfully!');
    }
}
