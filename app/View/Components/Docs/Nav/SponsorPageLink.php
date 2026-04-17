<?php

namespace App\View\Components\Docs\Nav;

use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationRelease;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SponsorPageLink extends Component
{
    /**
     * Create a new component instance.
     */
    public $document = null;

    public function __construct(public $user, public $documentation, public $release)
    {
        //
        $this->document = DocumentationDocument::where('documentation_id', $documentation->id)
            ->when(!empty($release), function ($query) use ($release) {
                $query->where('release_id', $release->id);
            })
            ->where('type', 'sponsors')
            ->where('status', 'live')
            ->latest()
            ->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docs.nav.sponsor-page-link');
    }
}
