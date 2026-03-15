<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationSocialLink;
use App\Models\SocialMediaPlatform;
use App\Models\User;
use Illuminate\Http\Request;

class SocialLinksController extends Controller
{
    //
    public function edit(User $user, Documentation $documentation)
    {
        $socialMediaPlatforms = SocialMediaPlatform::orderBy('name', 'ASC')->get();

        $docSocialMediaLinks = DocumentationSocialLink::where('documentation_id', $documentation->id)
            ->get()
            ->keyBy('social_media_platform_id');

        foreach ($socialMediaPlatforms as $platform) {
            $platform->doc_link = $docSocialMediaLinks[$platform->id]->url ?? null;
        }
        $title = "Social Links";

        return view('user.documentation.social-links.social-links-editor', compact(
            'socialMediaPlatforms',
            'documentation',
            'title'
        ));
    }

    public function updateSocialLinks(User $user, Documentation $documentation, Request $request)
    {
        $platformIds = $request->platform_id;
        $links = $request->social_media_link;

        foreach ($platformIds as $index => $platformId) {

            $url = $links[$index] ?? null;

            if (!empty($url)) {

                DocumentationSocialLink::updateOrCreate(
                    [
                        'documentation_id' => $documentation->id,
                        'social_media_platform_id' => $platformId
                    ],
                    [
                        'url' => $url
                    ]
                );
            } else {

                DocumentationSocialLink::where('documentation_id', $documentation->id)
                    ->where('social_media_platform_id', $platformId)
                    ->delete();
            }
        }

        return redirect()->back()->with('success', 'Social links updated successfully.');
    }
}
