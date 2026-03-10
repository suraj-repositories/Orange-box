<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationPage;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function show(User $user, $slug, $version, $path = null)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = DocumentationRelease::where('version', $version)
            ->where('documentation_id', $documentation->id)
            ->firstOrFail();

        $top5Releases = DocumentationRelease::where('documentation_id', $documentation->id)
            ->latest()
            ->limit(5)
            ->get();

        abort_unless($documentation->user_id === $user->id, 404);

        $segments = $path ? explode('/', $path) : [];

        $currentPage = null;
        $parentId = null;

        foreach ($segments as $segment) {

            $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
                ->where('release_id', $release->id)
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
                ->where('release_id', $release->id)
                ->whereNull('parent_id')
                ->where('is_published', 1)
                ->orderBy('sort_order')
                ->first();

            if (!$currentPage) {
                abort(404, 'No pages found!');
            }

            while ($currentPage && $currentPage->type === 'folder') {

                $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
                    ->where('release_id', $release->id)
                    ->where('parent_id', $currentPage->id)
                    ->where('is_published', 1)
                    ->orderBy('sort_order')
                    ->first();

                if (!$currentPage) {
                    abort(404, 'Folder is empty!');
                }
            }

            $currentPage->load('parent');

            $path = $this->buildFullPath($currentPage);

            return redirect()->route('docs.show', [
                'user' => $user->username,
                'slug' => $slug,
                'version' => $version,
                'path' => $path,
            ]);
        }

        $pages = DocumentationPage::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
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
            'nextPath',
            'release',
            'version',
            'top5Releases',
            'user',
        ));
    }

    public function switchVersion(User $user, $slug, $version, $path = null)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug)
            ->firstOrFail();

        $release = DocumentationRelease::where('version', $version)
            ->where('documentation_id', $documentation->id)
            ->firstOrFail();

        $segments = $path ? explode('/', $path) : [];

        $currentPage = null;
        $parentId = null;

        foreach ($segments as $segment) {

            $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
                ->where('release_id', $release->id)
                ->where('parent_id', $parentId)
                ->where('slug', $segment)
                ->where('is_published', 1)
                ->first();

            if (!$currentPage) {
                $currentPage = null;
                break;
            }

            $parentId = $currentPage->id;
        }

        if ($currentPage) {
            return redirect()->route('docs.show', [
                'user' => $user->username,
                'slug' => $slug,
                'version' => $version,
                'path' => $path,
            ]);
        }

        $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->whereNull('parent_id')
            ->where('is_published', 1)
            ->orderBy('sort_order')
            ->first();

        if (!$currentPage) {
            abort(404);
        }

        while ($currentPage && $currentPage->type === 'folder') {

            $currentPage = DocumentationPage::where('documentation_id', $documentation->id)
                ->where('release_id', $release->id)
                ->where('parent_id', $currentPage->id)
                ->where('is_published', 1)
                ->orderBy('sort_order')
                ->first();

            if (!$currentPage) {
                abort(404);
            }
        }

        $currentPage->load('parent');

        $newPath = $this->buildFullPath($currentPage);

        return redirect()->route('docs.show', [
            'user' => $user->username,
            'slug' => $slug,
            'version' => $version,
            'path' => $newPath,
        ]);
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
