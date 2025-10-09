<?php

namespace App\View\Components\Project;

use App\Models\ProjectBoard;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProjectModuleListComponent extends Component
{
    public $projectBoard;
    public $limit;
    /**
     * Create a new component instance.
     */
    public function __construct(ProjectBoard $projectBoard, $limit = null)
    {
        //
        $this->projectBoard = $projectBoard;
        $this->limit = $limit;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $modules  = null;
        if (empty($this->limit)) {
            $modules = $this->projectBoard->modules()->paginate(10);
        } else {
            $modules = $this->projectBoard->modules()->paginate($this->limit);
        }
        return view('components.project.project-module-list-component', compact('modules'));
    }
}
