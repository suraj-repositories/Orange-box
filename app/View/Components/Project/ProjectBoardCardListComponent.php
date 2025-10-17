<?php

namespace App\View\Components\Project;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProjectBoardCardListComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $projectBoards)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.project.project-board-card-list-component');
    }
}
