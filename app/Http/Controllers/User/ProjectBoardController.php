<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ColorTag;
use App\Models\FolderFactory;
use App\Models\ProjectBoard;
use App\Models\User;
use App\Services\FileService;
use App\Services\MarkdownService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectBoardController extends Controller
{
    //
    protected $fileService;
    protected $markdownService;
    public function __construct(FileService $fileService, MarkdownService $markdownService)
    {
        $this->fileService = $fileService;
        $this->markdownService = $markdownService;
    }

    public function index(User $user)
    {
        $projectBoards = ProjectBoard::with('colorTag')->where('user_id', $user->id)->orderBy('id', 'desc')->paginate(15);
        return view('user.project_tracker.project_board.project_board_list', compact('projectBoards'));
    }

    public function create()
    {
        $tagColors = ColorTag::get();

        return view('user.project_tracker.project_board.project_board_form', compact('tagColors'));
    }

    public function store(User $user, Request $request, MarkdownService $markdownService)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_tag' => ['nullable', 'exists:color_tags,id'],
            'description' => 'nullable|string|max:3000',
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

        $data = array_merge($validated, [
            'user_id' => $user->id,
            'preview_text' => $markdownService->toPlainText($validated['description']),
            'thumbnail' => $request->hasFile('thumbnail') ? $this->fileService->uploadFile($request->file('thumbnail'), 'project_board') : null,
            'color_tag_id' => $validated['color_tag']
        ]);

        ProjectBoard::create($data);

        return redirect()->to(authRoute('user.project-board'))->with('success', 'Project Board Created Successfully!');
    }

    public function show(User $user, $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();

        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }

        $query =  $projectBoard->modules()
            ->withCount('projectModuleUsers')
            ->with('limitedUsers');

        $projectModules = $query->orderBy('id', 'desc')->paginate();
        $tasks = $projectBoard->tasks()->latest()->take(10)->paginate();

        return view('user.project_tracker.project_board.project_board_show', compact('projectBoard', 'projectModules', 'tasks'));
    }

    public function edit(User $user, $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();

        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }
        $tagColors = ColorTag::get();

        return view('user.project_tracker.project_board.project_board_form', compact('projectBoard', 'tagColors'));
    }


    public function update(User $user, string $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)
            ->where('slug', $slug)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_tag' => ['nullable', 'exists:color_tags,id'],
            'description' => 'nullable|string|max:3000',
            'start_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('end_date') && $value > $request->end_date) {
                        $fail('The start date must be before or equal to the end date.');
                    }
                },
            ],
            'end_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('start_date') && $value < $request->start_date) {
                        $fail('The end date must be after or equal to the start date.');
                    }
                },
            ],
        ]);

        $thumbnailPath = $projectBoard->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $this->fileService->deleteIfExists($projectBoard->thumbnail);
            $thumbnailPath = $this->fileService->uploadFile($request->file('thumbnail'), 'project_board');
        }

        $data = [
            'title' => $validated['title'],
            'color_tag_id' => $validated['color_tag'] ?? $projectBoard->color_tag_id,
            'description' => $validated['description'] ?? null,
            'preview_text' => $this->markdownService->toPlainText($validated['description'] ?? ''),
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'thumbnail' => $thumbnailPath,
        ];

        $projectBoard->update($data);
        $projectBoard->refresh();

        return redirect()
            ->to(authroute('user.project-board.show', ['slug' => $projectBoard->slug]))
            ->with('success', 'Project Board updated successfully!');
    }

    public function destroy(User $user,  string $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();

        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }
        Gate::authorize('delete', $projectBoard);

        $projectBoard->delete();

        return redirect()->to(authRoute('user.project-board'))->with('success', 'Project Board Deleted Successfully!');
    }
}
