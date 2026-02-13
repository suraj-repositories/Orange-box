<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationPage;
use App\Models\User;
use App\Services\GitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class DocumentationPagesController extends Controller
{
    public function __construct(public GitService $gitService) {}

    public function index(User $user, Documentation $documentation)
    {
        $pages = DocumentationPage::where('documentation_id', $documentation->id)
            ->whereNull('parent_id')
            ->where('is_published', 1)
            ->orderBy('sort_order')
            ->with('childrenRecursive')
            ->get();


        return view('user.documentation.documentation_pages', [
            'documentation' => $documentation,
            'pages' => $pages,
        ]);
    }

    public function getDocumentationPage(User $user, DocumentationPage $docPage, Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $docPage
        ]);
    }

    public function createPage(User $user, Documentation $documentation, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_uuid' => 'nullable|exists:documentation_pages,uuid',
            'title' => 'required|string|max:256',
            'sort_order' => 'required|min:0|max:500',
            'type' => 'required|string|in:file,folder'
        ]);

        if ($validator->fails()) {
            return response()->json('error', $validator->errors()->first());
        }

        try {
            $page = DB::transaction(function () use ($request, $documentation, $user) {

                $parentId = DocumentationPage::where('uuid', $request->parent_uuid)->value('id');

                DocumentationPage::where('documentation_id', $documentation->id)
                    ->where('parent_id', $parentId)
                    ->where('sort_order', '>=', $request->sort_order)
                    ->increment('sort_order');

                return DocumentationPage::create([
                    'user_id' => $user->id,
                    'documentation_id' => $documentation->id,
                    'parent_id' => $parentId,
                    'type' => $request->type,
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'sort_order' => $request->sort_order,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Page Created successfully!',
                'data' => $page
            ]);
        } catch (Throwable $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function updateContent(User $user, DocumentationPage $docPage, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $docPage->content = $request->content;
            $docPage->save();

            return response()->json([
                'success' => true,
                'message' => 'Content updated successfully!',
                'data' => $docPage
            ]);
        } catch (Throwable $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function loadContentFromGit(User $user, DocumentationPage $docPage, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'git_link' => 'required|url|starts_with:https://'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $content = $this->gitService->loadGitPageContent($request->git_link);

            $docPage->content = $content;
            $docPage->save();

            return response()->json([
                'success' => true,
                'message' => 'Content updated successfully!',
                'data' => $docPage
            ]);
        } catch (Throwable $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function renamePageOrFolder(User $user, DocumentationPage $docPage, Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'new_name' => 'required|string|max:256',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $docPage->title = $request->new_name;
        $docPage->slug = Str::slug($request->new_name);
        $docPage->save();
        return response()->json([
            'success' => true,
            'message' => "Rename successfully!",
            'data' => $docPage
        ]);
    }
}
