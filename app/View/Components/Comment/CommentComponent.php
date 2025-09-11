<?php

namespace App\View\Components\Comment;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommentComponent extends Component
{
    public $comments;
    public $commentable;
    public $totalComments = 0;
    /**
     * Create a new component instance.
     */
    public function __construct($commentable = null)
    {
         $this->comments = collect();
        if(!empty($commentable)){
            $this->comments = $commentable->topLevelComments()->orderBy('id', 'desc')->paginate(20);
            $this->totalComments = $commentable->totalCommentsCount();
        }
        $this->commentable = $commentable;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.comment.comment-component');
    }
}
