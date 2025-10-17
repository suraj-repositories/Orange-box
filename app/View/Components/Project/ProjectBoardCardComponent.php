<?php

namespace App\View\Components\Project;

use App\Models\ProjectBoard;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProjectBoardCardComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ProjectBoard $projectBoard)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.project.project-board-card-component');
    }
}
