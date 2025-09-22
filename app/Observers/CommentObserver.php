<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    public function creating(Comment $comment)
    {
        if ($comment->parent_id) {
            $parent = Comment::find($comment->parent_id);

            if ($parent) {
                $comment->root_id = $parent->root_id ?? $parent->id;
            }
        } else {
            $comment->root_id = null;
        }
    }

    public function created(Comment $comment)
    {
        if (!$comment->parent_id && !$comment->root_id) {
            $comment->root_id = $comment->id;
            $comment->saveQuietly();
        }
    }

}
