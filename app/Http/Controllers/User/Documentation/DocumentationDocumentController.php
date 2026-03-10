<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\DocumentationDocument;
use App\Models\File;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DocumentationDocumentController extends Controller
{
    //
    public function __construct(public FileService $fileService) {}

    public function uploadEditorImages(User $user, Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Image is required!',
                'url'     => '',
            ]);
        }

        $uploadedFile = $request->file('image');
        $filePath = $this->fileService->uploadFile($uploadedFile, 'documentation_doc');

        if ($filePath) {
            try {
                $file = File::create([
                    'user_id'   => $user->id,
                    'file_name'     => $this->fileService->getFileName($uploadedFile),
                    'file_path'     => $filePath,
                    'mime_type'     => $this->fileService->getMimeType($uploadedFile),
                    'file_size'     => $uploadedFile->getSize() ?? null,
                    'fileable_type' => DocumentationDocument::class,
                    'fileable_id'   => null,
                    'is_temp'   => true
                ]);

                return response()->json([
                    'success' => 1,
                    'file' => [
                        'url'    => $file->getRelativePath(),
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => 0,
                    'file' => [
                        'url'    => ''
                    ]
                ]);
            }
        }

        return response()->json([
            'success' => 0,
            'file' => [
                'url'    => ''
            ]
        ]);
    }

    public function fetchUrlData(Request $request)
    {
        $url = $request->input('url');

        if (!$url) {
            return response()->json([
                'success' => 0,
                'message' => 'No URL provided.'
            ], 400);
        }

        try {
            $response = Http::get($url);

            if (!$response->ok()) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Unable to fetch URL'
                ]);
            }

            $html = $response->body();

            $baseUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
            $title = $this->getMeta($html, 'title');
            $description = $this->getMeta($html, 'description');
            $image = $this->getMeta($html, 'image');
            $icon = $this->getMeta($html, 'icon', $baseUrl);

            return response()->json([
                'success' => 1,
                'meta' => [
                    'title' => $title ?? $url,
                    'description' => $description ?? '',
                    'image' => [
                        'url' => empty($image) ? ($icon ?? $baseUrl . '/favicon.ico') : $image

                    ],
                    'icon' => $icon ?? $baseUrl . '/favicon.ico'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => 'Error fetching URL',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function getMeta($html, $key, $baseUrl = null)
    {
        if ($key === 'title' && preg_match("/<title>(.*?)<\/title>/i", $html, $matches)) {
            return $matches[1];
        }

        if (preg_match('/<meta.*?(?:name|property)=[\'"](og:' . $key . '|' . $key . ')[\'"].*?content=[\'"](.*?)[\'"].*?>/i', $html, $matches)) {
            return $matches[2];
        }
        if ($key === 'icon' && preg_match('/<link[^>]+rel=["\'](?:shortcut\s+icon|icon)["\'][^>]+>/i', $html, $matches)) {
            if (preg_match('/href=["\']([^"\']+)["\']/', $matches[0], $hrefMatch)) {
                $iconUrl = $hrefMatch[1];

                if ($baseUrl && !preg_match('/^https?:\/\//i', $iconUrl)) {
                    $iconUrl = rtrim($baseUrl, '/') . '/' . ltrim($iconUrl, '/');
                }

                return $iconUrl;
            }
        }
        return null;
    }

    public function fetchMediaFromUrl(Request $request)
    {
        if (!$request->has('url')) {
            return response()->json([
                'success' => 0,
                'file' => [
                    'url' => ''
                ]
            ]);
        }

        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $request->url
            ]
        ]);
    }

    public function changeStatus(User $user, DocumentationDocument $document, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $document->status = $request->status;
        $document->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!'
        ]);
    }
}
