<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    //
    public function index(Request $request)
    {

        $hasFilter = collect(['search', 'from_date', 'to_date'])
            ->some(fn($field) => $request->filled($field));

        $faqs = Faq::when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            });
        })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            })
            ->orderBy('sort_order', 'asc')
            ->paginate(10);
        $title = "FAQ's";
        return view('admin.faq.faq-list', compact('faqs', 'title', 'hasFilter'));
    }

    public function updateStatus(Request $request, Faq $faq)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'status' => $faq->is_active
            ]);
        }

        $faq->update([
            'is_active' => $request->is_active
        ]);

        return response()->json([
            'success' => true,
            'status' => $faq->is_active
        ]);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function create()
    {
        $faq = null;
        return view('admin.faq.faq-form', compact('faq'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer'   => ['required', 'string', 'max:5000'],
        ]);

        $sortOrder = (Faq::max('sort_order') ?? 0) + 1;

        Faq::create([
            'question'   => $validated['question'],
            'answer'     => $validated['answer'],
            'is_active'  => false,
            'sort_order' => $sortOrder,
        ]);

        return redirect()
            ->route('admin.faq.index')
            ->with('success', 'FAQ created successfully!');
    }

    public function edit(Faq $faq){
        return view('admin.faq.faq-form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer'   => ['required', 'string', 'max:5000'],
        ]);

        $faq->update([
            'question' => $validated['question'],
            'answer'   => $validated['answer'],
        ]);

        return redirect()
            ->route('admin.faq.index')
            ->with('success', 'FAQ updated successfully!');
    }
}
