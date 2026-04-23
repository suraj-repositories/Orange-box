<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationFaq;
use App\Models\DocumentationPartner;
use App\Models\DocumentationRelease;
use App\Models\DocumentationSponsor;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentationDocumentController extends Controller
{
    //
    public function index(User $user, $documentationSlug, $version, $type)
    {

        $documentation = Documentation::where('user_id', $user->id)
            ->where('url', $documentationSlug ?? '')
            ->firstOrFail();

        $release = null;
        if ($version != 'all') {
            $release = DocumentationRelease::where('version', $version)
                ->where('documentation_id', $documentation->id)
                ->firstOrFail();
        }

        $uuid = null;
        if ($type == 'custom') {
            $uuid = request('u');
        }

        $document = DocumentationDocument::where('documentation_id', $documentation->id)
            ->when(!empty($release), function ($query) use ($release) {
                $query->where('release_id', $release->id);
            })
            ->when(!empty($uuid), function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            })
            ->where('type', $type)
            ->where('status', 'live')
            ->latest()
            ->firstOrFail();

        $type = $document->type;


        switch ($type) {

            case 'faq':
                $pageData = $this->faqPageData($user, $documentation, $release, $document);
                return view('docs.faqs',  $pageData);
                break;

            case 'sponsors':
                $pageData = $this->sponsorPageData($user, $documentation, $release, $document);
                return view('docs.sponsors', $pageData);
                break;

            case 'partners':
                $pageData = $this->partnersPageData($user, $documentation, $release, $document);
                return view('docs.partner.partners', $pageData);
                break;

            default:
                break;
        }

        return view('docs.extras.index', compact('documentation', 'release', 'document', 'user'));
    }

    private function faqPageData($user, $documentation, $release, $document)
    {
        $faqs = DocumentationFaq::where('documentation_document_id', $document->id)
            ->where('is_active', true)
            ->paginate(20);

        return [
            'user' => $user,
            'documentation' => $documentation,
            'release' => $release,
            'faqs' => $faqs,
            'document' => $document
        ];
    }

    private function sponsorPageData($user, $documentation, $release, $document)
    {
        $title = 'Sponsors';
        $sponsorDocument = $document;
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

        return [
            'title' => $title,
            'user' => $user,
            'release' => $release,
            'documentation' => $documentation,
            'sponsorDocument' => $sponsorDocument,
            'sponsors' => $sponsors
        ];
    }

    private function partnersPageData($user, $documentation, $release, $document)
    {
        $title = 'Partners';

        $partners = DocumentationPartner::where('documentation_document_id', $document->id)
            ->where('status', 'active')
            ->where('is_spotlight_partner', false)
            ->orderBy('sort_order')
            ->latest()
            ->take(5)
            ->get();

        $spotlightPartner = DocumentationPartner::where('documentation_document_id', $document->id)
            ->where('status', 'active')
            ->where('is_spotlight_partner', true)
            ->first();

        return [
            'title' => $title,
            'user' => $user,
            'release' => $release,
            'documentation' => $documentation,
            'partners' => $partners,
            'spotlightPartner' => $spotlightPartner,
            'document' => $document,
        ];
    }
}
