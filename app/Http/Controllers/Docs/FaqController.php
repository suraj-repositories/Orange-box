<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationFaq;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    //
    public function index(User $user, $slug, $version)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = DocumentationRelease::where('version', $version)
            ->where('documentation_id', $documentation->id)
            ->firstOrFail();

        $faqs = DocumentationFaq::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->where('is_active', true)
            ->paginate(20);


        return view('docs.faqs', compact('user', 'documentation', 'release', 'faqs'));
    }
}
