<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Comments extends Component
{
    public $comments;
    public $commentable;
    /**
     * Create a new component instance.
     */
    public function __construct($commentable = null)
    {
        //
        $this->comments = collect();
        if(!empty($commentable)){
            $this->comments = $commentable->topLevelComments()->orderBy('id', 'desc')->paginate(20);
        }
        $this->commentable = $commentable;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {


        return view('components.comments');
    }
}
