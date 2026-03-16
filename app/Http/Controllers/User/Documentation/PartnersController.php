<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationPartner;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class PartnersController extends Controller
{
    public function __construct(public FileService $fileService) {}

    //
    public function index(User $user, Documentation $documentation, Request $request)
    {

        $title = "Partners";
        $partners = DocumentationPartner::where('documentation_id', $documentation->id)
            ->orderByDesc('is_spotlight_partner')
            ->orderBy('sort_order')
            ->paginate(10);

        return view('user.documentation.partners.partners-list', compact(
            'title',
            'partners',
            'documentation'
        ));
    }

    public function create(User $user, Documentation $documentation, Request $request)
    {
        $title = 'Create Partener';
        return view('user.documentation.partners.partners-form', compact('user', 'documentation', 'title'));
    }

    public function edit(User $user, DocumentationPartner $partner)
    {
        $documentation = $partner->documentation;
        $title = 'Edit Partener';
        return view('user.documentation.partners.partners-form', compact('user', 'documentation', 'partner', 'title'));
    }

    public function save(User $user, Documentation $documentation, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website_url' => ['nullable', 'url'],
            'location' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],

            'logo' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:4096'],
        ]);


        if ($request->filled('partner_id')) {

            $partner = DocumentationPartner::where('documentation_id', $documentation->id)
                ->where('id', $request->partner_id)
                ->firstOrFail();
        } else {

            $partner = new DocumentationPartner();
            $partner->documentation_id = $documentation->id;

            $partner->sort_order =
                DocumentationPartner::where('documentation_id', $documentation->id)->max('sort_order') + 1;
        }

        $partner->name = $validated['name'];
        $partner->slug = Str::slug($validated['name']);
        $partner->website_url = $validated['website_url'] ?? null;
        $partner->location = $validated['location'] ?? null;
        $partner->short_description = $validated['short_description'] ?? null;
        $partner->description = $validated['description'] ?? null;


        if ($request->hasFile('logo')) {

            if ($this->fileService->fileExists($partner->logo)) {
                $this->fileService->deleteIfExists($partner->logo);
            }

            $partner->logo = $this->fileService->uploadFile(
                $request->file('logo'),
                "documentation/partners/logo"
            );
        }

        if ($request->hasFile('banner')) {

            if ($this->fileService->fileExists($partner->banner)) {
                $this->fileService->deleteIfExists($partner->banner);
            }

            $partner->banner = $this->fileService->uploadFile(
                $request->file('banner'),
                "documentation/partners/banner"
            );
        }

        $partner->save();

        return redirect()
            ->to(authRoute('user.documentation.partners.index', ['documentation' =>  $documentation]))
            ->with('success', 'Partner saved successfully.');
    }

    public function updateStatus(User $user, DocumentationPartner $partner, Request $request)
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
            $partner->status = $request->status;
            $partner->save();

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

    public function markSpotlight(User $user, DocumentationPartner $partner)
    {
        try {
            DocumentationPartner::where('id', '!=', $partner->id)->update([
                'is_spotlight_partner' => false
            ]);
            DocumentationPartner::where('id', $partner->id)->update([
                'is_spotlight_partner' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Set to sportlight'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroy(User $user, DocumentationPartner $partner)
    {
        try {
            if (!empty($partner->logo)) {
                $this->fileService->deleteIfExists($partner->logo);
            }
            if (!empty($partner->banner)) {
                $this->fileService->deleteIfExists($partner->banner);
            }

            $partner->delete();

            return redirect()->back()->with('success', 'Partner deleted successfully');
        } catch (Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
