<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationFaq;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    //
    public function index(User $user, Documentation $documentation, DocumentationRelease $release)
    {
        $faqs = DocumentationFaq::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->orderBy('position', 'asc')
            ->paginate(10);

        $title = "Faq's";

        return view('user.documentation.faq.faq-list', compact('faqs', 'user', 'documentation', 'release', 'title'));
    }

    public function store(User $user, Documentation $documentation, DocumentationRelease $release, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        DocumentationFaq::create([
            'documentation_id' => $documentation->id,
            'release_id' => $release->id,
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question added successfully!'
        ]);
    }
}
