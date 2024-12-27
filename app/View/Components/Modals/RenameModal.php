<?php

namespace App\View\Components\Modals;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RenameModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $prevResourceName = "",
        public string $formActionUrl = "",
        public string $modalId = ""
    )
    {
        //



    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modals.rename-modal');
    }
}
