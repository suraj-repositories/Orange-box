<?php

namespace App\View\Components\Docs;

use App\Models\DocumentationDocument;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarSponsors extends Component
{
    public $sponsors = [];
    public $document = null;
    public $user = null;
    /**
     * Create a new component instance.
     */
    public function __construct(public $documentation, public $page)
    {
        //
        $this->sponsors = $documentation->sponsors()
            ->where('tier', 'platinum')
            ->where('status', 'active')
            ->take(10)
            ->get();
        $r = $page->release_id;


        $this->document = DocumentationDocument::where('documentation_id', $page->documentation_id)
            ->when(!empty($r), function ($query) use ($r) {
                $query->where('release_id', $r);
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
        return view('components.docs.sidebar-sponsors');
    }
}
