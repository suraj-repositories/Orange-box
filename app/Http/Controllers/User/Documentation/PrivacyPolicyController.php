<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    //
    public function updateOrNewPage(User $user, Documentation $documentation, DocumentationRelease $release)
    {
        $title = 'Privacy Policy';

        return view('user.documentation.privacy-policy.privacy-policy-form', [
            'title' => $title,
            'user' => $user,
            'documentation' => $documentation,
            'release' => $release
        ]);
    }

    public function saveOrUpdate(User $user, Documentation $documentation, DocumentationRelease $release, Request $request)
    {
        $validated = $request->validate([
            'editor_content' => 'required',
        ]);

        DocumentationDocument::create([
            'documentation_id' => $documentation->id,
            'release_id' => $release->id,
            'title' => 'Privacy Policy',
            'slug' => 'privacy',
            'type' => 'type',
            'content' => $validated['content'],
            'status' => 'inactive'
        ]);
    }
}
