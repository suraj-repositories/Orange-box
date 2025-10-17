<?php

namespace App\View\Components\Project;

use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ProjectModuleListComponent extends Component
{
    public ?ProjectBoard $projectBoard = null;

    /**
     * Create a new component instance.
     */
    public function __construct(public $modules,  public $filter = [], ?ProjectBoard $projectBoard = null)
    {
        $this->projectBoard = $projectBoard;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.project.project-module-list-component');
    }
}
