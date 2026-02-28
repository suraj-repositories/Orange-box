<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\DeleteCommentableCommentsJob;
use App\Models\File;
use App\Models\SyntaxStore;
use App\Models\User;
use App\Services\EditorJsService;
use Illuminate\Http\Request;
use App\Services\FileService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use SweetAlert2\Laravel\Swal;
use Throwable;

class SyntaxStoreController extends Controller
{
    protected $fileService;
    protected $editorService;

    public function __construct(FileService $fileService, EditorJsService $editorService)
    {
        $this->fileService = $fileService;
        $this->editorService = $editorService;
    }

    public function index()
    {
        $items = SyntaxStore::orderBy('id', 'desc')->paginate(10);

        return view('user.thinkspace.syntax_store.syntax_store_list', [
            'title' => "Public Syntax Store",
            'items' => $items,
        ]);
    }
    public function mySyntaxStores(User $user)
    {
        $items = SyntaxStore::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);

        return view('user.thinkspace.syntax_store.syntax_store_list',  [
            'title' => "My Syntax Store",
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('user.thinkspace.syntax_store.syntax_store_form');
    }

    public function store(User $user, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'editor_content' => 'required',
            'submit_status' => 'required|in:publish,draft',
        ]);

        try {
            $store = SyntaxStore::create([
                'title' => $validated['title'],
                'preview_text' => $this->editorService->jsonToPlainText($validated['editor_content']),
                'content' => $validated['editor_content'],
                'status' => $validated['submit_status'],
                'user_id' => $user->id
            ]);


            $usedFilePaths = $this->editorService->extractFiles($validated['editor_content']);
            $unusedFiles = File::where('user_id', $user->id)
                ->where('is_temp', true)
                ->whereNotIn('file_path', $usedFilePaths)
                ->select('id', 'file_path', 'is_temp', 'file_name')
                ->get();

            DB::transaction(function () use ($store, $user, $usedFilePaths, $unusedFiles) {
                File::where('user_id', $user->id)->whereIn('file_path', $usedFilePaths)->update([
                    'is_temp' => false,
                    'fileable_id' => $store->id,
                    'fileable_type' => SyntaxStore::class,
                ]);

                $unusedIds = $unusedFiles->pluck('id');
                $unusedPaths = $unusedFiles->pluck('file_path')->toArray();;
                $this->fileService->deleteAllIfExists($unusedPaths);
                File::whereIn('id', $unusedIds)->forceDelete();
            });
            return redirect()->to(authRoute('user.syntax-store.show', ['syntaxStore' => $store]));
        } catch (Throwable $ex) {
             Swal::error([
                'title' => 'Error!',
                'text' => $ex->getMessage()
            ]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function show(User $user, SyntaxStore $syntaxStore, Request $request)
    {
        Gate::authorize('view', $syntaxStore);
        return view('user.thinkspace.syntax_store.show_syntax_store', compact('syntaxStore', 'user'));
    }

    public function edit(User $user, SyntaxStore $syntaxStore, Request $request)
    {
        Gate::authorize('update', $syntaxStore);
        return view('user.thinkspace.syntax_store.syntax_store_form', compact('user', 'syntaxStore'));
    }

    public function update(User $user, SyntaxStore $syntaxStore, Request $request)
    {
        Gate::authorize('update', $syntaxStore);
        $validated = $request->validate([
            'title' => 'required',
            'editor_content' => 'required',
            'submit_status' => 'required|in:publish,draft',
        ]);

        try {
            $syntaxStore->update([
                'title'         => $validated['title'],
                'preview_text'  => $this->editorService->jsonToPlainText($validated['editor_content']),
                'content'       => $validated['editor_content'],
                'status'        => $validated['submit_status'],
            ]);

            $usedFilePaths = $this->editorService->extractFiles($validated['editor_content']);

            $unusedFiles = File::where('user_id', $user->id)
                ->where('fileable_type', SyntaxStore::class)
                ->where('fileable_id', $syntaxStore->id)
                ->whereNotIn('file_path', $usedFilePaths)
                ->get(['id', 'file_path']);

            DB::transaction(function () use ($syntaxStore, $usedFilePaths, $unusedFiles) {
                File::whereIn('file_path', $usedFilePaths)->update([
                    'is_temp'       => false,
                    'fileable_id'   => $syntaxStore->id,
                    'fileable_type' => SyntaxStore::class,
                ]);

                $unusedIds = $unusedFiles->pluck('id');
                $unusedPaths = $unusedFiles->pluck('file_path')->toArray();

                $this->fileService->deleteAllIfExists($unusedPaths);
                File::whereIn('id', $unusedIds)->forceDelete();
            });


            Swal::success([
                'title' => 'Success!',
                'text' => 'Syntax Updated Successfully!'
            ]);

            return redirect()->to(authRoute('user.syntax-store.show', ['syntaxStore' => $syntaxStore]));
        } catch (Throwable $th) {

            Swal::error([
                'title' => 'Error!',
                'text' => $th->getMessage()
            ]);

            return redirect()->back();
        }
    }


    public function storeEditorImages(User $user, Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Image is required!',
                'url'     => '',
            ]);
        }

        $uploadedFile = $request->file('image');
        $filePath = $this->fileService->uploadFile($uploadedFile, 'syntax_store');

        if ($filePath) {
            try {
                $file = File::create([
                    'user_id'   => $user->id,
                    'file_name'     => $this->fileService->getFileName($uploadedFile),
                    'file_path'     => $filePath,
                    'mime_type'     => $this->fileService->getMimeType($uploadedFile),
                    'file_size'     => $uploadedFile->getSize() ?? null,
                    'fileable_type' => SyntaxStore::class,
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

    public function destroy(User $user, SyntaxStore $syntaxStore)
    {
        Gate::authorize('delete', $syntaxStore);

        DeleteCommentableCommentsJob::dispatch(
            get_class($syntaxStore),
            $syntaxStore->id
        );
        $syntaxStore->delete();

        Swal::success([
            'title' => 'Success!',
            'text' => 'Syntax Deleted Successfully!',
        ]);
        return redirect()->to(authRoute('user.syntax-store'));
    }

    public function like(User $user, SyntaxStore $syntaxStore, Request $request)
    {
        if ($syntaxStore->likedBy($user->id)) {
            $syntaxStore->removeLike($user->id);
        } else {
            $syntaxStore->like($user->id);
        }
        return response()->json([
            'status' => 'success',
            'likes' => $syntaxStore->likesCount(),
            'dislikes' => $syntaxStore->dislikesCount(),
            'is_liked' => $syntaxStore->likedBy($user->id),
            'is_disliked' => $syntaxStore->dislikedBy($user->id)
        ]);
    }

    public function dislike(User $user, SyntaxStore $syntaxStore, Request $request)
    {
        if ($syntaxStore->dislikedBy($user->id)) {
            $syntaxStore->removeLike($user->id);
        } else {
            $syntaxStore->dislike($user->id);
        }
        return response()->json([
            'status' => 'success',
            'likes' => $syntaxStore->likesCount(),
            'dislikes' => $syntaxStore->dislikesCount(),
            'is_liked' => $syntaxStore->likedBy($user->id),
            'is_disliked' => $syntaxStore->dislikedBy($user->id)
        ]);
    }
}
