<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ThinkPad;
use App\Models\ThinkSpace;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;

class ThinkPadController extends Controller
{

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    //
    public function index()
    {
        //
        $thinkpads = ThinkPad::paginate(15);

        return view('user.thinkspace.think_pad.think_pad_list', compact('thinkpads'));
    }

    public function create()
    {
        //
        return view('user.thinkspace.think_pad.think_pad_form');
    }

    public function edit(User $user, ThinkPad $dailyDigest) {}
}
