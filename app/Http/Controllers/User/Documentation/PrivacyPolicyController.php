<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class PrivacyPolicyController extends Controller
{
    //
    public function updateOrNewPage(User $user, Documentation $documentation, DocumentationRelease $release)
    {
        $title = 'Privacy Policy';

        $documentationDocument = DocumentationDocument::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->where('type', 'privacy')
            ->first();

        return view('user.documentation.privacy-policy.privacy-policy-form', [
            'title' => $title,
            'user' => $user,
            'documentationDocument' => $documentationDocument,
            'documentation' => $documentation,
            'release' => $release
        ]);
    }

    public function saveOrUpdate(User $user, Documentation $documentation, DocumentationRelease $release, Request $request)
    {
        $validated = $request->validate([
            'editor_content' => 'required',
        ]);

        try {
            DocumentationDocument::updateOrCreate(
                [
                    'documentation_id' => $documentation->id,
                    'release_id' => $release->id,
                    'type' => 'privacy'
                ],
                [
                    'title' => 'Privacy Policy',
                    'content' => $validated['editor_content'],
                    'status' => 'inactive'
                ]
            );

            return redirect()->to(authRoute('user.documentation.show', [
                'documentation' => $documentation,
                'release' => $release
            ]));
        } catch (Throwable $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
