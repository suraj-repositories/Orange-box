<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //
    public function store(Request $request){
        $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id'   => 'required|integer',
            'message'          => 'required|string|max:1000',
            'parent_id'        => 'nullable|integer|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id'         => Auth::id(),
            'parent_id'       => $request->parent_id,
            'commentable_type'=> $request->commentable_type,
            'commentable_id'  => $request->commentable_id,
            'message'         => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'data'    => $comment->load('user', 'replies'),
        ]);
    }

    public function loadComment(Request $request){
        $size = 5;
        $modelClass = $request->commentable_type;
        $commentable = $modelClass::find($request->commentable_id);

        if (!$commentable) {
            return response()->json([
                'status' => 404,
                'data' => '',
                'message' => 'Commentable not found'
            ]);
        }

        $comments = $commentable->topLevelComments()
        ->orderBy('id', 'desc')
        ->paginate($size, ['*'], 'page', $request->page ?? 1);


        if ($comments->isEmpty()) {
            return response()->json([
                'status' => 204,
                'data' => view('components.no-data')->render(),
                'message' => 'No comments available'
            ]);
        }

        if ($request->page > $comments->lastPage()) {
            return response()->json([
                'status' => 204,
                'data' => '',
                'message' => 'No more comments to load'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => view('components.comment.comments', ['comments' => $comments])->render(),
            'message' => 'Comments fetched successfully'
        ]);
}


}
