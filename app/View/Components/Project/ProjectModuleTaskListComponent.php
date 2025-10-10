<?php

namespace App\View\Components\Project;

use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\Task;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProjectModuleTaskListComponent extends Component
{
    public ?ProjectBoard $projectBoard;
    public ?ProjectModule $projectModule;
    public $limit;
    /**
     * Create a new component instance.
     */
    public function __construct(?ProjectBoard $projectBoard = null, ?ProjectModule $projectModule = null, $limit = null)
    {
        //
        $this->projectBoard = $projectBoard;
        $this->projectModule = $projectModule;
        $this->limit = $limit;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $tasks = collect();

        if (!empty($this->projectBoard)) {
            $tasks = $this->projectBoard->tasks()->orderBy('id', 'desc')->take(10)->get();
        }

        if (!empty($this->projectModule)) {
            $tasks = $this->projectModule->tasks()->paginate(10);
        }
        return view('components.project.project-module-task-list-component', compact('tasks'));
    }
}
