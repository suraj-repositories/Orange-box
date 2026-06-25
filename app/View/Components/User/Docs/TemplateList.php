<?php

namespace App\View\Components\User\Docs;

use App\Services\ThemeService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TemplateList extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct(public $templates, ThemeService $themeService)
    {
        //

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user.docs.template-list');
    }
}
