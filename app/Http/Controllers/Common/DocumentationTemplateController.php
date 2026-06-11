<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\DocumentationTemplate;
use App\Models\TemplateReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentationTemplateController extends Controller
{
    //
    public function addReview(DocumentationTemplate $template,  Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'comment' => 'nullable|string|max:500',
            'rating' => 'required|numeric|min:0|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        TemplateReview::create([
            'documentation_template_id' => $template->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review added successfully!',
            'total_reviews' => $template->reviews()->count()
        ]);
    }

    public function loadMore(DocumentationTemplate $template, Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = 5;

        $reviews = $template->reviews()->latest()->offset($offset)->take($limit)->get();

        if ($reviews) {
            return response()->json([
                'success' => true,
                'data' => view('components.review.review-list-component', ['reviews' => $reviews])->render(),
                'has_more' => $template->reviews()->latest()->offset($offset + $limit)->limit(5)->count() > 0,
                'new_offset' => $offset + $limit,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No more reviews available!'
        ]);
    }

    public function deleteTemplateReview(TemplateReview $review, Request $request)
    {

        if ($review->user_id != Auth::id()) {
            if (!$request->is_ajax) {
                abort(403, 'Unauthorized');
            }
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $review->delete();

        if (!$request->is_ajax) {
            return redirect()->back()->with('success', 'Review deleted successfully!');
        }


        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully!'
        ]);
    }
}
