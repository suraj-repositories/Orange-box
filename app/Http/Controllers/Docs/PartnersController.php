<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationPartner;
use App\Models\DocumentationRelease;
use App\Models\User;
use Illuminate\Http\Request;

class PartnersController extends Controller
{
    public function partnersAll(User $user, $slug, $version, Request $request)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')

            ->firstOrFail();

        $release = null;
        if ($version != 'all') {
            $release = DocumentationRelease::where('version', $version)
                ->where('documentation_id', $documentation->id)
                 ->where('is_published', true)
                ->firstOrFail();
        }

        $title = 'Partners';
        $searchable = true;

        $document = DocumentationDocument::where('documentation_id', $documentation->id)
            ->when(!empty($release), function ($query) use ($release) {
                $query->where('release_id', $release->id);
            })
            ->where('type', 'partners')
            ->where('status', 'live')
            ->latest()
            ->firstOrFail();

        $partners = DocumentationPartner::where('documentation_document_id', $document->id)
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
                'searchable',
                'document'
            )
        );
    }

    public function show(User $user, $slug, $version, $uuid)
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

    public function partnersSearchComponent(User $user, $slug, $version, Request $request)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = null;
        if ($version != 'all') {
            $release = DocumentationRelease::where('version', $version)
                ->where('documentation_id', $documentation->id)
                 ->where('is_published', true)
                ->firstOrFail();
        }

        $search = $request->search;

        $document = DocumentationDocument::where('documentation_id', $documentation->id)
            ->when(!empty($release), function ($query) use ($release) {
                $query->where('release_id', $release->id);
            })
            ->where('type', 'partners')
            ->where('status', 'live')
            ->latest()
            ->firstOrFail();

        $partners = DocumentationPartner::where('documentation_document_id', $document->id)
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
