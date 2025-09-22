<?php

namespace App\View\Components\Comment;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommentComponent extends Component
{
    public $commentable;
    public $comment;
    public $replies;
    /**
     * Create a new component instance.
     */
    public function __construct($commentable, $comment, $replies)
    {
         $this->commentable = $commentable;
         $this->comment = $comment;
         $this->replies = $replies;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.comment.comment-reply');
    }
}
