<?php

namespace App\View\Components\User\Docs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TreeNode extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $page)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user.docs.tree-node');
    }
}
