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

}
