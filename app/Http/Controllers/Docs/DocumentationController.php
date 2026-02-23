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
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        abort_unless($documentation->user_id === $user->id, 404);

        $segments = $path ? explode('/', $path) : [];

        $currentPage = null;
        $parentId = null;

        foreach ($segments as $segment) {

            $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
                ->where('parent_id', $parentId)
                ->where('slug', $segment)
                ->where('is_published', 1)
                ->first();

            if (!$currentPage) {
                abort(404, 'Page not found!');
            }

            $parentId = $currentPage->id;
        }


        if (count($segments) === 0) {

            $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->where('is_published', 1)
                ->first();

            if (!$currentPage) {
                abort(404, 'No pages found!');
            }

            while ($currentPage && $currentPage->type === 'folder') {

                $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
                    ->where('parent_id', $currentPage->id)
                    ->where('is_published', 1)
                    ->orderBy('sort_order')
                    ->first();

                if (!$currentPage) {
                    abort(404, 'Folder is empty!');
                }
            }
        }

        $pages = DocumentationPage::where('documentation_id', $documentation->id)
            ->whereNull('parent_id')
            ->where('is_published', 1)
            ->orderBy('sort_order')
            ->with('childrenRecursive')
            ->get();

        $flatPages = $this->flattenPages($pages);
        $filePages = $flatPages->where('type', 'file')->values();

        $previousPage = null;
        $nextPage = null;

        if ($currentPage && $currentPage->type === 'file') {

            $currentIndex = $filePages->search(
                fn($p) => $p->id === $currentPage->id
            );

            if ($currentIndex !== false) {
                $previousPage = $filePages->get($currentIndex - 1);
                $nextPage = $filePages->get($currentIndex + 1);
            }
        }

        $previousPath = null;
        $nextPath = null;

        if ($previousPage) {
            $previousPage->load('parent');
            $previousPath = $this->buildFullPath($previousPage);
        }

        if ($nextPage) {
            $nextPage->load('parent');
            $nextPath = $this->buildFullPath($nextPage);
        }

        return view('docs.index', compact(
            'documentation',
            'pages',
            'currentPage',
            'previousPage',
            'nextPage',
            'previousPath',
            'nextPath'
        ));
    }

    private function flattenPages($pages, &$flat = [])
    {
        foreach ($pages as $page) {
            $flat[] = $page;

            if ($page->childrenRecursive->isNotEmpty()) {
                $this->flattenPages($page->childrenRecursive, $flat);
            }
        }

        return collect($flat);
    }

    private function buildFullPath($page)
    {
        $segments = [];

        while ($page) {
            array_unshift($segments, $page->slug);
            $page = $page->parent;
        }

        return implode('/', $segments);
    }
}
