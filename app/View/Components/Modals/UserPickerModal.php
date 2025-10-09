<?php

namespace App\View\Components\Modals;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserPickerModal extends Component
{
    public $title;
    /**
     * Create a new component instance.
     */
    public function __construct($title = "Select User")
    {
        //
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modals.user-picker-modal');
    }
}
