<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationPage;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function show(User $user, $slug, $path = null)
    {
        $documentation = Documentation::where('user_id', $user->id)->where('url', $slug ?? '')->firstOrFail();

        abort_unless($documentation->user_id === $user->id, 404);

        $segments = $path ? explode('/', $path) : [];

        $currentPage = null;
        $parentId = null;

        foreach ($segments as $segment) {
            $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
                ->where('parent_id', $parentId)
                ->where('slug', $segment)
                ->where('is_published', 1)
                ->firstOrFail();

            $parentId = $currentPage->id;
        }

        $pages = DocumentationPage::where('documentation_id', $documentation->id)
            ->whereNull('parent_id')
            ->where('is_published', 1)
            ->orderBy('sort_order')
            ->with('childrenRecursive')
            ->get();

        return view('docs.index', compact('documentation', 'pages', 'currentPage'));
    }
}
