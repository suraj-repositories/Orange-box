<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\File;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DocumentationDocumentController extends Controller
{
    public function __construct(public FileService $fileService) {}


    public function index(User $user, Documentation $documentation)
    {
        $title = "Pages";
        $releases = $documentation->releases;

        $formattedVersion = $releases->map(function ($r) {
            return [
                'id'        => $r->id,
                'name'      => $r->version,
                'label'     => $r->title,
                'date'      => $r->released_at ? $r->released_at->format('M Y') : 'N/A',
                'current'   => (bool) $r->is_current,
                'published' => (bool) $r->is_published,
            ];
        })->values();

        return view(
            'user.documentation.documents.document-pages',
            compact('title', 'documentation', 'releases', 'formattedVersion')
        );
    }

    public function list(User $user, Documentation $documentation, Request $request)
    {

        $query = DocumentationDocument::where('documentation_id', $documentation->id)
            ->with('release');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('release_id')) {
            $query->where(function ($q) use ($request) {

                $q->whereNull('release_id')
                    ->orWhere('release_id', $request->release_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->get()->map(fn($d) => $this->formatDocument($d));

        $defaultSidebarData = [
            'total' => 0,
            'privacy' => 0,
            'terms' => 0,
            'code_of_conduct' => 0,
            'guide' => 0,
            'sponsors' => 0,
            'partners' => 0,
            'faq' => 0,
            'cookie' => 0,
            'custom' => 0,
        ];

        $sidebarData =  DocumentationDocument::where('documentation_id', $documentation->id)
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        $merged = array_merge($defaultSidebarData, $sidebarData);
        $merged['total'] = array_sum($sidebarData);

        return response()->json([
            'success' => true,
            'data'    => $documents,
            'sidebar_data' => $merged
        ]);
    }

    public function store(User $user, Documentation $documentation, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|in:privacy,terms,code_of_conduct,sponsors,partners,guide,faq,cookie,custom',

            'status'     => 'required|string|in:draft,live,off',
            'description'    => 'nullable|string',
            'scope'      => 'required|string|in:all,specific',
            'release_id' => 'nullable|exists:documentation_releases,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }


        $releaseId = ($request->scope === 'specific') ? $request->release_id : null;

        $document = DocumentationDocument::create([
            'documentation_id' => $documentation->id,
            'release_id'       => $releaseId,
            'title'            => $request->title,
            'type'             => $request->type,
            'description'          => $request->description ?? '',
            'status'           => $request->status,
        ]);

        $document->load('release');

        return response()->json([
            'success' => true,
            'message' => "{$document->title} created successfully.",
            'data'    => $this->formatDocument($document),
        ], 201);
    }

    public function show(User $user, Documentation $documentation, DocumentationDocument $document)
    {
        $this->authorizeDocument($documentation, $document);

        $document->load('release');

        return response()->json([
            'success' => true,
            'data'    => $this->formatDocument($document),
        ]);
    }

    public function update(User $user, Documentation $documentation, DocumentationDocument $document, Request $request)
    {
        $this->authorizeDocument($documentation, $document);

        $validator = Validator::make($request->all(), [
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|in:privacy,terms,code_of_conduct,sponsors,partners,guide,faq,cookie,custom',

            'status'     => 'required|string|in:draft,live,off',
            'description'    => 'nullable|string',
            'scope'      => 'required|string|in:all,specific',
            'release_id' => 'nullable|exists:documentation_releases,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }



        $releaseId = ($request->scope === 'specific') ? $request->release_id : null;

        $document->update([
            'release_id' => $releaseId,
            'title'      => $request->title,

            'type'       => $request->type,
            'description'    => $request->description ?? '',
            'status'     => $request->status,
        ]);

        $document->load('release');

        return response()->json([
            'success' => true,
            'message' => "{$document->title} updated successfully.",
            'data'    => $this->formatDocument($document),
        ]);
    }

    public function destroy(User $user, Documentation $documentation, DocumentationDocument $document)
    {
        $this->authorizeDocument($documentation, $document);

        $title = $document->title;
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => "{$title} deleted successfully.",
        ]);
    }

    public function toggle(User $user, Documentation $documentation, DocumentationDocument $document)
    {
        $this->authorizeDocument($documentation, $document);

        $document->status = ($document->status === 'live') ? 'off' : 'live';
        $document->save();

        return response()->json([
            'success' => true,
            'message' => "{$document->title} is now {$document->status}.",
            'data'    => ['status' => $document->status],
        ]);
    }

    public function changeStatus(User $user, DocumentationDocument $document, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:live,draft,off',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $document->status = $request->status;
        $document->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
        ]);
    }

    public function uploadEditorImages(User $user, Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['status' => 'error', 'message' => 'Image is required!', 'url' => '']);
        }

        $uploadedFile = $request->file('image');
        $filePath     = $this->fileService->uploadFile($uploadedFile, 'documentation_doc');

        if ($filePath) {
            try {
                $file = File::create([
                    'user_id'       => $user->id,
                    'file_name'     => $this->fileService->getFileName($uploadedFile),
                    'file_path'     => $filePath,
                    'mime_type'     => $this->fileService->getMimeType($uploadedFile),
                    'file_size'     => $uploadedFile->getSize() ?? null,
                    'fileable_type' => DocumentationDocument::class,
                    'fileable_id'   => null,
                    'is_temp'       => true,
                ]);

                return response()->json(['success' => 1, 'file' => ['url' => $file->getRelativePath()]]);
            } catch (\Exception $e) {
                return response()->json(['success' => 0, 'file' => ['url' => '']]);
            }
        }

        return response()->json(['success' => 0, 'file' => ['url' => '']]);
    }

    public function fetchUrlData(Request $request)
    {
        $url = $request->input('url');
        if (!$url) {
            return response()->json(['success' => 0, 'message' => 'No URL provided.'], 400);
        }

        try {
            $response = Http::get($url);
            if (!$response->ok()) {
                return response()->json(['success' => 0, 'message' => 'Unable to fetch URL']);
            }

            $html    = $response->body();
            $baseUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);

            return response()->json([
                'success' => 1,
                'meta'    => [
                    'title'       => $this->getMeta($html, 'title') ?? $url,
                    'description' => $this->getMeta($html, 'description') ?? '',
                    'image'       => ['url' => $this->getMeta($html, 'image') ?? ($this->getMeta($html, 'icon', $baseUrl) ?? $baseUrl . '/favicon.ico')],
                    'icon'        => $this->getMeta($html, 'icon', $baseUrl) ?? $baseUrl . '/favicon.ico',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => 0, 'message' => 'Error fetching URL', 'error' => $e->getMessage()]);
        }
    }

    public function fetchMediaFromUrl(Request $request)
    {
        if (!$request->has('url')) {
            return response()->json(['success' => 0, 'file' => ['url' => '']]);
        }
        return response()->json(['success' => 1, 'file' => ['url' => $request->url]]);
    }

    private function formatDocument(DocumentationDocument $doc): array
    {


        $typeRoutes = [
            'privacy' => 0,
            'terms' => 0,
            'code_of_conduct' => 0,
            'guide' => 0,
            'sponsors' =>  authRoute('user.documentation.document.sponsors.index', [
                'document' => $doc
            ]),
            'partners' => authRoute('user.documentation.partners.index', [
                'document' => $doc
            ]),
            'faq' => 0,
            'cookie' => 0,
            'custom' => 0,
        ];

        return [
            'id'         => $doc->id,
            'title'      => $doc->title,
            'type'       => $doc->type,
            'status'     => $doc->status,
            'description'    => $doc->description,
            'scope'      => $doc->release_id ? 'specific' : 'all',
            'release_id' => $doc->release_id,
            'release'    => $doc->release ? [
                'id'      => $doc->release->id,
                'version' => $doc->release->version,
                'title'   => $doc->release->title,
            ] : null,
            'link' =>  $typeRoutes[$doc->type] ?? null,
            'created_at' => $doc->created_at?->toISOString(),
            'updated_at' => $doc->updated_at?->toISOString(),
        ];
    }

    private function authorizeDocument(Documentation $documentation, DocumentationDocument $document): void
    {
        abort_if($document->documentation_id !== $documentation->id, 403, 'Unauthorized');
    }

    private function getMeta($html, $key, $baseUrl = null): ?string
    {
        if ($key === 'title' && preg_match("/<title>(.*?)<\/title>/i", $html, $m)) {
            return $m[1];
        }
        if (preg_match('/<meta.*?(?:name|property)=[\'"](og:' . $key . '|' . $key . ')[\'"].*?content=[\'"](.*?)[\'"].*?>/i', $html, $m)) {
            return $m[2];
        }
        if ($key === 'icon' && preg_match('/<link[^>]+rel=["\'](?:shortcut\s+icon|icon)["\'][^>]+>/i', $html, $m)) {
            if (preg_match('/href=["\']([^"\']+)["\']/', $m[0], $h)) {
                $iconUrl = $h[1];
                if ($baseUrl && !preg_match('/^https?:\/\//i', $iconUrl)) {
                    $iconUrl = rtrim($baseUrl, '/') . '/' . ltrim($iconUrl, '/');
                }
                return $iconUrl;
            }
        }
        return null;
    }
}
