<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationPage;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentationReleaseController extends Controller
{
    //
    public function index(User $user, Documentation $documentation)
    {
        $title = 'Relealses';
        $releases = $documentation->releases()->latest('id')->paginate(10);

        return view('user.documentation.releases.release-list', compact('title', 'user', 'documentation', 'releases'));
    }

    public function save(User $user, Documentation $documentation, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'version' => 'required|string|min:1|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        if ($documentation->releases()
            ->where('version', $request->version)
            ->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This version already exists.'
            ]);
        }

        DB::transaction(function () use ($documentation, $request) {

            $currentRelease = $documentation->releases()
                ->latest()
                ->first();

            if (!$currentRelease) {
                throw new \Exception('No current release found.');
            }

            $newRelease = $documentation->releases()->create([
                'version' => $request->version,
                'is_current' => false,
                'is_published' => false,
                'released_at' => null
            ]);

            $pages = DocumentationPage::where('release_id', $currentRelease->id)->get();

            $idMap = [];

            foreach ($pages as $page) {

                $newPage = $page->replicate(['uuid']);

                $newPage->release_id = $newRelease->id;

                $oldParent = $page->parent_id;
                $newPage->parent_id = null;

                $newPage->save();

                $idMap[$page->id] = [
                    'new_id' => $newPage->id,
                    'old_parent' => $oldParent
                ];
            }

            foreach ($idMap as $oldId => $data) {

                if ($data['old_parent']) {

                    DocumentationPage::where('id', $data['new_id'])
                        ->update([
                            'parent_id' => $idMap[$data['old_parent']]['new_id'] ?? null
                        ]);
                }
            }

            $documents = DocumentationDocument::where('release_id', $currentRelease->id)->get();

            foreach ($documents as $document) {
                $newDocument = $document->replicate();
                $newDocument->release_id = $newRelease->id;
                $newDocument->save();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'New documentation version created successfully.'
        ]);
    }

    public function update(User $user, Documentation $documentation, DocumentationRelease $release, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'version' => 'required|string|min:1|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        if ($documentation->releases()
            ->where('version', $request->version)
            ->where('id', '!=', $release->id)
            ->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This version already exists.'
            ]);
        }

        $release->version = $request->version;
        $release->save();

        return response()->json([
            'success' => true,
            'message' => 'Release version title updated.'
        ]);
    }

    public function destroy(User $user, DocumentationRelease $release)
    {
        $documentation = $release->documentation;

        if (!$documentation) {
            abort(404, 'Documentation not found');
        }

        if ($user->id != $documentation->user_id) {
            abort(403, 'Unauthorized access!');
        }

        $release->delete();

        return redirect()->back()->with('success', 'Release Deleted Successfully!');
    }
}
