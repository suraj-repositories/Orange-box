<?php

namespace App\View\Components\Files;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FileListComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $items)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.files.file-list-component');
    }
}
