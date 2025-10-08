<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FolderFactory;
use App\Models\ProjectBoard;
use App\Models\User;
use App\Services\FileService;
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

    public function index() {
        $projectBoards = ProjectBoard::paginate(15);
        return view('user.project_tracker.project_board.project_board_list', compact('projectBoards'));
    }

    public function create()
    {
        $tagColors = collect(config('constants.TAG_COLORS'))
            ->map(fn($tag) => (object) $tag);;

        return view('user.project_tracker.project_board.project_board_form', compact('tagColors'));
    }

    public function store(User $user, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_tag' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = array_merge($validated, [
            'user_id' => $user->id,
            'thumbnail' => $request->hasFile('thumbnail') ? $this->fileService->uploadFile($request->file('thumbnail'), 'project_board') : null,
        ]);

        ProjectBoard::create($data);

        return redirect()->back()->with('success', 'Project Board Created Successfully!');
    }
}
