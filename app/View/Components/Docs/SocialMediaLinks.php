<?php

namespace App\View\Components\Docs;

use App\Models\Documentation;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SocialMediaLinks extends Component
{
    public $socialLinks = [];
    /**
     * Create a new component instance.
     */


    public function __construct(Documentation $documentation)
    {
        //
        $this->socialLinks = $documentation->socialLinks()
            ->with('platform')
            ->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docs.social-media-links');
    }
}
