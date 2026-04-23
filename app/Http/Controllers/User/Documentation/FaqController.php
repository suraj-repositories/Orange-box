<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationFaq;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class FaqController extends Controller
{
    //
    public function index(User $user, DocumentationDocument $document)
    {
        $faqs = DocumentationFaq::where('documentation_document_id', $document->id)
            ->orderBy('position', 'asc')
            ->paginate(10);

        $title = "Faq's";

        return view(
            'user.documentation.faq.faq-list',
            [
                'faqs' => $faqs,
                'user' => $user,
                'document' => $document,
                'documentation' => $document->documentation,
                'release' => $document->release,
                'title' => $title
            ]
        );
    }

    public function store(User $user, DocumentationDocument $document, Request $request)
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

        if ($request->filled('faq_id')) {

            $faq = DocumentationFaq::where('id', $request->faq_id)
                ->where('documentation_document_id', $document->id)
                ->firstOrFail();

            $faq->update([
                'question' => $request->question,
                'answer' => $request->answer
            ]);

            $message = 'Question updated successfully!';
        } else {

            DocumentationFaq::create([
                'documentation_document_id' => $document->id,
                'question' => $request->question,
                'answer' => $request->answer
            ]);

            $message = 'Question added successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function updateStatus(User $user, DocumentationFaq $faq, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $faq->is_active = $request->status == 'active' ? true : false;
            $faq->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroy(User $user, DocumentationFaq $faq)
    {
        try {
            $faq->delete();
            return redirect()->back()->with('success', 'Question deleted successfully!');
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Error : " . $th->getMessage());
        }
    }
}
