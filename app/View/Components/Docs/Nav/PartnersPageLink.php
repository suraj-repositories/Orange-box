<?php

namespace App\View\Components\Docs\Nav;

use App\Models\DocumentationDocument;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PartnersPageLink extends Component
{

    public $document = null;
    /**
     * Create a new component instance.
     */

    public function __construct(public $user, public $documentation, public $release)
    {
        //
        $this->document = DocumentationDocument::where('documentation_id', $documentation->id)
            ->when(!empty($release), function ($query) use ($release) {
                $query->where('release_id', $release->id);
            })
            ->where('type', 'partners')
            ->where('status', 'live')
            ->latest()
            ->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docs.nav.partners-page-link');
    }
}
