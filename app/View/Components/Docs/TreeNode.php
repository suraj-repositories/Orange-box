<?php

namespace App\View\Components\Docs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TreeNode extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $page , public $open = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docs.tree-node');
    }
}
