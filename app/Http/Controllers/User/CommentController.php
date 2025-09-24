<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Psy\CodeCleaner\ReturnTypePass;

class CommentController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id'   => 'required|integer',
            'message'          => 'required|string|max:1000',
            'parent_id'        => 'nullable|integer|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id'         => Auth::id(),
            'parent_id'       => $request->parent_id,
            'commentable_type' => $request->commentable_type,
            'commentable_id'  => $request->commentable_id,
            'message'         => $request->message,
        ]);

        $htmlView = "";
        if (!empty($request->parent_id)) {
            $modelClass = $request->commentable_type;
            $commentable = $modelClass::find($request->commentable_id);
            $parentComment = Comment::find($request->parent_id);

            $htmlView = view('components.comment.comment-reply')->with([
                'commentable' => $commentable,
                'comment' => $parentComment,
                'replies' => collect([$comment]),
                'is_newly_created' => true
            ])->render();
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'data'    => $comment->load('user', 'replies'),
            'html' => $htmlView
        ]);
    }

    public function loadComment(Request $request)
    {
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

        $comments = $commentable->topLevelComments()->with(['user'])
            ->orderBy('id', $request->order ?? 'desc')
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
            'data' => view('components.comment.comments', ['comments' => $comments, 'commentable' => $commentable])->render(),
            'message' => 'Comments fetched successfully'
        ]);
    }

    public function loadReplies(Request $request)
    {
        $size = 5;
        $modelClass = $request->commentable_type;
        $commentable = $modelClass::find($request->commentable_id);
        $comment = Comment::find($request->comment_id);

        if (!$commentable) {
            return response()->json([
                'status' => 404,
                'data' => '',
                'message' => 'Commentable not found'
            ]);
        }
        if (!$comment) {
            return response()->json([
                'status' => 404,
                'data' => '',
                'message' => 'Comment not found'
            ]);
        }

        $replies = $comment->topLevelReplies()
            ->when($request->has('new_identification_keys'), function ($query) use ($request) {
                $query->whereNotIn('id', $request->new_identification_keys);
            })
            ->paginate($size, ['*'], 'page', $request->page ?? 1);


        if ($replies->isEmpty()) {
            return response()->json([
                'status' => 204,
                'data' => view('components.no-data')->render(),
                'message' => 'No replies available'
            ]);
        }

        if ($request->page > $replies->lastPage()) {
            return response()->json([
                'status' => 204,
                'data' => '',
                'message' => 'No more replies to load'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => view('components.comment.comment-reply', ['commentable' => $commentable, 'comment' => $comment, 'replies' => $replies])->render(),
            'message' => 'Replies fetched successfully'
        ]);
    }

    public function destroy(User $user, Comment $comment, Request $request){
        if($comment->user->id != $user->id){
            return response()->json([
                'status' => 'error',
                'message' => 'Access deined!'
            ]);
        }
        $comment->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully!'
        ]);
    }
}
