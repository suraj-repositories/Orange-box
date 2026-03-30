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
            'docs.partner.partners',
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
            ->orderBy('sort_order')
            ->latest()
            ->paginate(6);

        return view(
            'docs.partner.partners',
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

    public function show(User $user, $slug, $uuid)
    {

        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = DocumentationRelease::where('documentation_id', $documentation->id)
            ->where('is_current', true)
            ->where('is_published', true)
            ->latest()->first();

        $partner = DocumentationPartner::where('uuid', $uuid)->firstOrFail();
        $title = 'Partners';

        return view('docs.partner.show-partner', compact([
            'title',
            'user',
            'release',
            'documentation',
            'partner'
        ]));
    }

    public function partnersSearchComponent(User $user, $slug, Request $request)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = DocumentationRelease::where('documentation_id', $documentation->id)
            ->where('is_current', true)
            ->where('is_published', true)
            ->latest()->first();

        $search = $request->search;

        $partners = DocumentationPartner::where('documentation_id', $documentation->id)
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%");
            })
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(6);

        return response()->json([
            'success' => true,
            'message' => 'Component fetched successfully!',
            'html' => view('components.docs.partners-list', compact('partners'))->render()
        ]);
    }
}
