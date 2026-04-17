<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationFaq;
use App\Models\DocumentationRelease;
use App\Models\DocumentationSponsor;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentationDocumentController extends Controller
{
    //
    public function index(User $user, $documentationSlug, $version, $slug)
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

        $document = DocumentationDocument::where('documentation_id', $documentation->id)
            ->when(!empty($release), function ($query) use ($release) {
                $query->where('release_id', $release->id);
            })
            ->where('slug', $slug)
            ->where('status', 'live')
            ->latest()
            ->firstOrFail();

        $type = $document->type;

        switch ($type) {

            case 'faq':
                $faqs = DocumentationFaq::where('documentation_id', $documentation->id)
                    ->where('release_id', $release->id)
                    ->where('is_active', true)
                    ->paginate(20);

                return view('docs.faqs', compact('user', 'documentation', 'release', 'faqs'));
                break;

            case 'sponsors':
                $pageData = $this->sponsorPageData($user, $documentation, $release, $document);
                return view('docs.sponsors', $pageData);
                break;

            case 'partners':

                break;

            default:
                break;
        }

        return view('docs.extras.index', compact('documentation', 'release', 'document', 'user'));
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
}
