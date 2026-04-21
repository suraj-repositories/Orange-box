<?php

namespace App\View\Components\Docs;

use App\Models\DocumentationDocument;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DocumentationDocumentNavItems extends Component
{

    public $documentationDocuments = [];
    /**
     * Create a new component instance.
     */
    public function __construct(public $user, public $documentation, public $release)
    {
        //

        $this->documentationDocuments = DocumentationDocument::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->whereNotIn('type', ['sponsors', 'partners'])
            ->select('status', 'type', 'id', 'title',  'documentation_id', 'release_id')
            ->get()
            ->keyBy('type');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docs.documentation-document-nav-items');
    }
}
