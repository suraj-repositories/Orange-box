<?php

namespace App\View\Components\Project;

use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\Task;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ProjectModuleTaskListComponent extends Component
{
    public ?ProjectBoard $projectBoard;
    public ?ProjectModule $projectModule;
    public $limit;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $tasks,
        ?ProjectBoard $projectBoard,
        ?ProjectModule $projectModule,
    ) {
        //
        $this->projectBoard = $projectBoard;
        $this->projectModule = $projectModule;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.project.project-module-task-list-component');
    }
}
