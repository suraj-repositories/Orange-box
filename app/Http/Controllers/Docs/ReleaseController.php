<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    //
    public function index(User $user, $slug)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $releases = DocumentationRelease::where('documentation_id', $documentation->id)
            ->where('is_published', true)
            ->latest()
            ->take(50)
            ->get();

        $latestStableRelease = DocumentationRelease::where('documentation_id', $documentation->id)
            ->where('is_current', true)
            ->where('is_published', true)
            ->latest()->first();

        $release = $documentation->latestRelease;
        $title = 'Releases';

        return view('docs.releases', compact('user', 'releases', 'release', 'title', 'documentation', 'latestStableRelease'));
    }
}
