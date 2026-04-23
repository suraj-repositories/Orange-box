<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationFaq;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    //
    public function index(User $user, $slug, $version)
    {
        dd($slug, $version);

        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = DocumentationRelease::where('version', $version)
            ->where('documentation_id', $documentation->id)
            ->firstOrFail();

        $document = DocumentationDocument::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->where('status', 'live')
            ->where('type', 'faq')
            ->first();

        $faqs = DocumentationFaq::where('documentation_document_id', $document->id)
            ->where('is_active', true)
            ->paginate(20);


        return view('docs.faqs', compact('user', 'documentation', 'release', 'faqs'));
    }
}
