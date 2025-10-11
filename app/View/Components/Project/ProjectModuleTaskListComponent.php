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
    public function __construct(
        ?ProjectBoard $projectBoard,
        ?ProjectModule $projectModule,
        $limit = null
    ) {
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

        if ($this->projectModule?->exists) {
            $tasks = $this->projectModule->tasks()->paginate(10);
        } elseif ($this->projectBoard?->exists) {
            $tasks = $this->projectBoard->tasks()->latest()->take($this->limit ?? 10)->get();
        }

        return view('components.project.project-module-task-list-component', compact('tasks'));
    }
}
