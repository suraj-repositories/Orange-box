<?php

namespace App\Http\Middleware;

use App\Models\Documentation;
use App\Models\DocumentationPage;
use App\Models\DocumentationRelease;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->route('user');

        $documentation = Documentation::where('user_id', $user?->id)
            ->where('url', $request->slug ?? '')
            ->first();

        $release = DocumentationRelease::where('version', $request->version)
            ->where('documentation_id', $documentation?->id ?? '')
            ->where('is_published', true)
            ->first();

        $top5Releases = DocumentationRelease::where('documentation_id', $documentation?->id)
            ->where('is_published', true)
            ->latest()
            ->limit(5)
            ->get();

        $pages = DocumentationPage::where('documentation_id', $documentation?->id)
            ->where('release_id', $release?->id)
            ->whereNull('parent_id')
            ->where('is_published', 1)
            ->orderBy('sort_order')
            ->with('childrenRecursive')
            ->get();


        view()->share([
            'user' => $user,
            'documentation' => $documentation,
            'release' => $release,
            'version' => $request->version,
            'top5Releases' => $top5Releases,
            'pages' => $pages,
        ]);



        return $next($request);
    }
}
