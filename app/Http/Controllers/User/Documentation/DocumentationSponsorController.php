<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\DocumentationDocument;
use App\Models\DocumentationSponsor;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class DocumentationSponsorController extends Controller
{
    public function __construct(public FileService $fileService) {}

    public function index(User $user, DocumentationDocument $document)
    {
        $title = 'Sponsors';

        $sponsors = $document->sponsors()->paginate(10);

        $sponsorDocument = DocumentationDocument::where('id', $document->id)
            ->where('type', 'sponsors')
            ->first();

        $documentation = $document->documentation;
        $release = $document->release;

        return view(
            'user.documentation.sponsor.sponsors-show',
            compact('user', 'documentation', 'title', 'sponsors', 'release', 'sponsorDocument')
        );
    }


    public function save(User $user, DocumentationDocument $document, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url',
            'description' => 'nullable|string',
            'tier' => 'nullable|in:platinum,gold,silver,bronze',
            'logo_light' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg,avif|max:2048',
            'logo_dark'  => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg,avif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        if ($request->filled('sponsor_id')) {
            $sponsor = DocumentationSponsor::where('documentation_document_id', $document->id)
                ->findOrFail($request->sponsor_id);
        } else {
            $sponsor = new DocumentationSponsor();
            $sponsor->documentation_document_id = $document->id;
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

    public function updateStatus(User $user, DocumentationSponsor $sponsor, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $sponsor->status = $request->status;
            $sponsor->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroy(User $user, DocumentationSponsor $sponsor)
    {
        try {

            if (!empty($sponsor->logo_light)) {
                $this->fileService->deleteIfExists($sponsor->logo_light);
            }
            if (!empty($sponsor->logo_dark)) {
                $this->fileService->deleteIfExists($sponsor->logo_dark);
            }

            $sponsor->delete();
            return redirect()->back()->with('success', 'Sponsor deleted successfully!');
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Error : " . $th->getMessage());
        }
    }

    public function updateContent(User $user, DocumentationDocument $document, Request $request)
    {

        $validated = $request->validate([
            'editor_content' => 'required',
        ]);

        try {
            $document->content =  $validated['editor_content'];
            $document->save();


            return redirect()->back()->with('success', 'Updated successfully!');
        } catch (Throwable $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
