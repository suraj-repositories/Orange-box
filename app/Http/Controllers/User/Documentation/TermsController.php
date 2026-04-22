<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class TermsController extends Controller
{
    public function editOrNewPage(User $user, DocumentationDocument $document)
    {
        $title = 'Terms';
        return view('user.documentation.terms.terms-editor', [
            'title' => $title,
            'user' => $user,
            'document' => $document,
            'documentation' => $document->documentation,
            'release' => $document->release
        ]);
    }

    public function update(User $user, DocumentationDocument $document, Request $request)
    {
        $validated = $request->validate([
            'editor_content' => 'required',
        ]);

        try {
            $document->content = $validated['editor_content'];
            $document->save();

            return redirect()->back()->with('success', 'Saved Successfully!');
        } catch (Throwable $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
