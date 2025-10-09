<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FolderFactory;
use App\Models\ProjectBoard;
use App\Models\User;
use App\Services\FileService;
use App\Services\MarkdownService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProjectBoardController extends Controller
{
    //
    protected $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(User $user)
    {
        $projectBoards = ProjectBoard::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(15);
        return view('user.project_tracker.project_board.project_board_list', compact('projectBoards'));
    }

    public function create()
    {
        $tagColors = collect(config('constants.TAG_COLORS'))
            ->map(fn($tag) => (object) $tag);;

        return view('user.project_tracker.project_board.project_board_form', compact('tagColors'));
    }

    public function store(User $user, Request $request, MarkdownService $markdownService)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_tag' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
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
        ]);

        ProjectBoard::create($data);

        return redirect()->back()->with('success', 'Project Board Created Successfully!');
    }

    public function show(User $user, $slug, Request $request)
    {
        $projectBoard = ProjectBoard::where('user_id', $user->id)->where('slug', $slug)->first();

        if (!$projectBoard) {
            abort(404, "Project Not Found!");
        }
        return view('user.project_tracker.project_board.project_board-show', compact('projectBoard'));
    }
}
