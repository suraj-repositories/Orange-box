<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationRelease;
use App\Models\DocumentationSponsor;
use App\Models\User;
use Illuminate\Http\Request;

class SponsorsController extends Controller
{
    //
    public function index(User $user, DocumentationRelease $release, $slug)
    {
        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $slug ?? '')
            ->firstOrFail();

        $release = DocumentationRelease::where('documentation_id', $documentation->id)
            ->where('is_current', true)
            ->where('is_published', true)
            ->latest()->first();

        $title = 'Sponsors';

        $sponsorDocument = DocumentationDocument::where('documentation_id', $documentation->id)
            // ->whereNull('release_id')
            ->where('type', 'sponsors')
            ->where('status', 'live')
            ->firstOrFail();

        $tierOrder = ['platinum', 'gold', 'silver', 'bronze'];

        $sponsors = DocumentationSponsor::where('documentation_document_id', $sponsorDocument->id)
            ->where('status', 'active')
            ->orderByRaw("FIELD(tier, 'platinum', 'gold', 'silver', 'bronze')")
            ->get()
            ->groupBy(function ($item) {
                return $item->tier ?? 'others';
            })
            ->sortBy(function ($items, $tier) use ($tierOrder) {
                $index = array_search($tier, $tierOrder);
                return $index === false ? 999 : $index;
            });



        return view(
            'docs.sponsors',
            compact(
                'title',
                'user',
                'release',
                'documentation',
                'sponsorDocument',
                'sponsors'
            )
        );
    }
}
