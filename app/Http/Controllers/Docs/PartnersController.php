<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationPartner;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;

class PartnersController extends Controller
{
    //

    public function index(User $user, $slug)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        // latest stable release
        $release = DocumentationRelease::where('documentation_id', $documentation->id)
            ->where('is_current', true)
            ->where('is_published', true)
            ->latest()->first();

        $title = 'Partners';

        $partners = DocumentationPartner::where('documentation_id', $documentation->id)
            ->where('status', 'active')
            ->where('is_spotlight_partner', false)
            ->orderBy('sort_order')
            ->latest()
            ->take(5)
            ->get();

        $spotlightPartner = DocumentationPartner::where('documentation_id', $documentation->id)
            ->where('status', 'active')
            ->where('is_spotlight_partner', true)
            ->first();


        return view(
            'docs.partners',
            compact(
                'title',
                'user',
                'release',
                'documentation',
                'partners',
                'spotlightPartner'
            )
        );
    }

    public function partnersAll(User $user, $slug, Request $request)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = DocumentationRelease::where('documentation_id', $documentation->id)
            ->where('is_current', true)
            ->where('is_published', true)
            ->latest()->first();

        $title = 'Partners';
        $searchable = true;

        $partners = DocumentationPartner::where('documentation_id', $documentation->id)
            ->where('status', 'active')
            ->where('is_spotlight_partner', false)
            ->orderBy('sort_order')
            ->latest()
            ->take(5)
            ->get();

        return view(
            'docs.partners',
            compact(
                'title',
                'user',
                'release',
                'documentation',
                'partners',
                'searchable'
            )
        );
    }
}
