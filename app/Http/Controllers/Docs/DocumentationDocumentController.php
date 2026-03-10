<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentationDocumentController extends Controller
{
    //
    public function index(User $user, $slug, $version, $type)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = DocumentationRelease::where('version', $version)
            ->where('documentation_id', $documentation->id)
            ->firstOrFail();

        $document = DocumentationDocument::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->where('type', $type)
            ->where('status', 'active')
            ->firstOrFail();

        return view('docs.extras.hot-pages', compact('documentation', 'release', 'document', 'user'));
    }
}
