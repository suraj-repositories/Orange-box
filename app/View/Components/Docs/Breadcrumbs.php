<?php

namespace App\View\Components\Docs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    /**
     * Create a new component instance.
     */

    public array $pathItems;

    public function __construct(public $documentation, public $currentPage, public $username, public $version)
    {
        $this->pathItems = array_values(
            array_filter(explode('/', trim($this->currentPage->full_path, '/')))
        );
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docs.breadcrumbs');
    }
}
