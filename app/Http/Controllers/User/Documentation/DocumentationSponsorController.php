<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationSponsor;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentationSponsorController extends Controller
{
    public function __construct(public FileService $fileService) {}

    public function index(User $user, Documentation $documentation, Request $request)
    {
        $title = 'Sponsors';

        $sponsors = $documentation->sponsors()->paginate(10);

        if ($request->has('v')) {
            $release = $documentation->releases()
                ->where('version', $request->get('v'))
                ->where('is_published', true)
                ->firstOrFail();
        } else {
            $release = $documentation->latestRelease;
        }

        return view(
            'user.documentation.sponsor.sponsors-show',
            compact('user', 'documentation', 'title', 'sponsors', 'release')
        );
    }

    public function save(User $user, Documentation $documentation, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url',
            'description' => 'nullable|string',
            'tier' => 'nullable|in:platinum,gold,silver,bronze',
            'logo_light' => 'nullable|image|max:2048',
            'logo_dark' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        if ($request->filled('sponsor_id')) {
            $sponsor = DocumentationSponsor::where('documentation_id', $documentation->id)
                ->findOrFail($request->sponsor_id);
        } else {
            $sponsor = new DocumentationSponsor();
            $sponsor->documentation_id = $documentation->id;
        }

        $sponsor->name = $request->name;
        $sponsor->website_url = $request->website_url;
        $sponsor->description = $request->description;
        $sponsor->tier = $request->tier;
        $sponsor->sort_order = $request->sort_order ?? 0;
        $sponsor->status = $request->status ?? 'active';

        if ($request->hasFile('logo_light')) {
            if ($sponsor->logo_light) {
                $this->fileService->deleteIfExists($sponsor->logo_light);
            }

            $sponsor->logo_light = $this->fileService->uploadFile(
                $request->file('logo_light'),
                'documentation/sponsors'
            );
        }

        if ($request->hasFile('logo_dark')) {
            if ($sponsor->logo_dark) {
                $this->fileService->deleteIfExists($sponsor->logo_dark);
            }

            $sponsor->logo_dark = $this->fileService->uploadFile(
                $request->file('logo_dark'),
                'documentation/sponsors'
            );
        }

        $sponsor->save();

        return response()->json([
            'success' => true,
            'message' => $request->filled('sponsor_id')
                ? 'Sponsor updated successfully.'
                : 'Sponsor created successfully.',
            'data' => $sponsor
        ]);
    }
}
