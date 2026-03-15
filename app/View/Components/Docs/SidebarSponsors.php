<?php

namespace App\View\Components\Docs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarSponsors extends Component
{
    public $sponsors = [];
    /**
     * Create a new component instance.
     */
    public function __construct(public $documentation)
    {
        //
        $this->sponsors = $documentation->sponsors()
            ->where('tier', 'platinum')
            ->where('status', 'active')
            ->take(10)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docs.sidebar-sponsors');
    }
}
