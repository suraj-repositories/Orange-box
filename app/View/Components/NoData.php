<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NoData extends Component
{
    public $message;
    public $icon;
    public $theme;
    public $isDecorated;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $message = 'No Data',
        $icon = 'bi bi-exclamation-octagon' ,
        $theme = 'warning',
        $isDecorated = false
        )
    {
        $this->message = $message;
        $this->icon = $icon;
        $this->theme = $theme;
        $this->isDecorated = $isDecorated;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.no-data');
    }
}
