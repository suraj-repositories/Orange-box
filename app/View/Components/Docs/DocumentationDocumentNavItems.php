<?php

namespace App\View\Components\Docs;

use App\Models\DocumentationDocument;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DocumentationDocumentNavItems extends Component
{

    public $documentationDocuments = [];
    public $customDocumentationDocuments = [];
    /**
     * Create a new component instance.
     */
    public function __construct(public $user, public $documentation, public $release)
    {


        $this->documentationDocuments = DocumentationDocument::where('documentation_id', $documentation->id)
            ->where(function ($q) use ($release) {
                $q->where('release_id', $release->id)
                    ->orWhereNull('release_id');
            })
            ->whereNotIn('type', ['sponsors', 'partners', 'custom'])
            ->orderByRaw("CASE WHEN release_id = ? THEN 0 ELSE 1 END", [$release->id])
            ->orderByDesc('id')
            ->get()
            ->unique('type')
            ->keyBy('type');

        $this->customDocumentationDocuments = DocumentationDocument::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->where('type', 'custom')
            ->select('status', 'type', 'id', 'title',  'documentation_id', 'release_id', 'uuid')
            ->get()
            ->keyBy('uuid');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docs.documentation-document-nav-items');
    }
}
