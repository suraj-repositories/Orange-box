<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\FolderFactory;
use App\Models\Icon;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FolderFactoryController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(User $user)
    {
        $icons = Icon::where('category', 'folder')->where('status', 'active')->orderBy('order', 'asc')->get();

        $folderFactories = FolderFactory::with('icon')
            ->withSum('files', 'file_size')
            ->where('user_id', $user->id)
            ->orderBy('name', 'asc')
            ->paginate(15);

        $folderFactories->getCollection()->transform(function ($folder) {
            $folder->readable_file_size = $this->fileService->getFormattedSize($folder->files_sum_file_size ?? 0);
            return $folder;
        });

        $totalFilesAndSize = File::where('user_id', $user->id)
            ->where('fileable_type', FolderFactory::class)
            ->groupBy('user_id')
            ->select(
                'user_id',
                DB::raw("SUM(file_size) as total_size"),
                DB::raw("COUNT(id) as total_files")
            )
            ->first();

        $totalFiles = $totalFilesAndSize->total_files;
        $totalSize = $this->fileService->getFormattedSize($totalFilesAndSize->total_size);

        return view('user.orbit_zone.folder_factory.folder_factory_list', compact('icons', 'folderFactories', 'totalFiles', 'totalSize'));
    }

    public function showFiles(User $user, $slug, Request $request)
    {
        $folderFactory = FolderFactory::where('slug', $slug)->where('user_id', $user->id)->first();
        if (!$folderFactory) {
            abort(404, 'Folder not exists!');
        }
        return view('user.orbit_zone.folder_factory.folder_factory_files_list', compact('folderFactory'));
    }

    public function create(User $user)
    {
        $folderFactories = FolderFactory::where('user_id', $user->id)->orderBy('name', 'asc')->get();

        return view('user.orbit_zone.folder_factory.file_upload_form', compact('folderFactories'));
    }

    public function store(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('folder_factories')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
            'icon' => 'nullable|integer|exists:icons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }

        $folderFactory = FolderFactory::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'icon_id' => $request->icon ?? null,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Folder Created Successfully!',
            'folderFactory' => $folderFactory,
        ]);
    }

    public function update(User $user, FolderFactory $folderFactory, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('folder_factories')
                    ->ignore($folderFactory->id)
                    ->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
            'icon' => 'nullable|integer|exists:icons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }

        Gate::authorize('update', $folderFactory);

        $folderFactory->name = $request->name;
        $folderFactory->icon_id = $request->icon;
        $folderFactory->slug =  Str::slug($request->name);
        $folderFactory->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Folder Updated Successfully!',
            'folderFactory' => $folderFactory,
        ]);
    }


    public function destroy(User $user, FolderFactory $folderFactory, Request $request)
    {
        Gate::authorize('delete', $folderFactory);

        $folderFactory->delete();

        if ($request->isJson() || $request->wantsJson()) {
            return response()->json([
                'status' => 200,
                'message' => 'Folder factory destroyed successfully!'
            ]);
        }
        return redirect()->back()->with('success', 'Folder factory destroyed successfully!');
    }

    /* ================================ BEGIN - CHUNK UPLOAD ================================*/


    public function uploadStatus(Request $request)
    {
        $uploadId = $request->query('uploadId');
        if (!$uploadId) {
            return response()->json(['error' => 'Invalid upload ID'], 400);
        }

        $disk = Storage::disk('private');
        $tempDir = $disk->path('files/temp/' . $uploadId);

        if (!file_exists($tempDir)) {
            return response()->json(['uploadedChunks' => []]);
        }

        $uploadedChunks = array_diff(scandir($tempDir), ['.', '..']);
        $uploadedChunks = array_map('intval', $uploadedChunks);
        sort($uploadedChunks);
        return response()->json(['uploadedChunks' => $uploadedChunks]);
    }

    public function uploadChunk(User $user, Request $request)
    {
        $fileNameRaw = $request->get('fileName');
        $chunkIndexRaw = $request->get('chunkIndex');
        $totalChunksRaw = $request->get('totalChunks');
        $folderIdRaw = $request->get('folderId');
        $uploadId = $request->get('uploadId');

        if (!$uploadId) {
            return response()->json(['error' => 'Missing uploadId'], 400);
        }

        if (!$fileNameRaw) {
            return response()->json(['error' => 'Missing fileName'], 400);
        }

        if ($chunkIndexRaw === null || $chunkIndexRaw === '') {
            return response()->json(['error' => 'Missing chunkIndex'], 400);
        }

        if ($totalChunksRaw === null || $totalChunksRaw === '') {
            return response()->json(['error' => 'Missing totalChunks'], 400);
        }

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'Missing file payload'], 400);
        }

        $chunkIndex = is_numeric($chunkIndexRaw) ? (int)$chunkIndexRaw : null;
        $totalChunks = is_numeric($totalChunksRaw) ? (int)$totalChunksRaw : 0;
        $fileName = (string)$fileNameRaw;
        $folderId = $folderIdRaw ? (int)$folderIdRaw : null;

        if (!is_int($chunkIndex) || $chunkIndex < 0) {
            return response()->json(['error' => 'Invalid chunkIndex'], 400);
        }

        if ($totalChunks <= 0) {
            return response()->json(['error' => 'Invalid totalChunks'], 400);
        }

        $disk = Storage::disk('private');
        $tempDir = $disk->path('files/temp/' . $uploadId);

        $folderCacheKey   = "upload_folder_{$user->id}_{$uploadId}";
        $progressCacheKey = "upload_progress_{$user->id}_{$uploadId}";

        if ($chunkIndex === 0) {
            if (!$folderId) {
                return response()->json(['error' => 'Missing folderId for initial chunk'], 400);
            }

            $folder = FolderFactory::where('id', $folderId)->where('user_id', $user->id)->first();
            if (!$folder) {
                return response()->json(['error' => 'Folder not found or not allowed'], 403);
            }

            Cache::put($folderCacheKey, $folder->id, 3600);
            Cache::put($progressCacheKey, 0, 3600);

            if (!file_exists($tempDir)) {
                if (!@mkdir($tempDir, 0777, true) && !is_dir($tempDir)) {
                    return response()->json(['error' => 'Server error creating temp dir'], 500);
                }
            }
        } else {
            $cachedFolderId = Cache::get($folderCacheKey);
            if (!$cachedFolderId) {
                return response()->json(['error' => 'Upload session expired or not initialized (missing chunk 0)'], 400);
            }
            $folderId = $cachedFolderId;
        }

        try {
            $uploaded = $request->file('file')->move($tempDir, (string)$chunkIndex);
            if ($uploaded === false) {
                return response()->json(['error' => 'Failed to save chunk'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save chunk', 'details' => $e->getMessage()], 500);
        }

        if (!Cache::has($progressCacheKey)) {
            Cache::put($progressCacheKey, 1, 3600);
        } else {
            Cache::increment($progressCacheKey);
        }

        $currentProgress = Cache::get($progressCacheKey, 0);

        if ($currentProgress >= $totalChunks) {
            $finalFileName = 'F-' . Str::uuid() . '-' . $fileName;
            $finalPath = $disk->path('files/' . $finalFileName);

            try {
                $finalFile = fopen($finalPath, 'w');
                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = $tempDir . '/' . $i;
                    if (!file_exists($chunkPath)) {
                        fclose($finalFile);
                        return response()->json(['error' => "Missing chunk {$i} during merge"], 500);
                    }
                    $chunk = fopen($chunkPath, 'r');
                    while ($data = fread($chunk, 1024 * 1024)) {
                        fwrite($finalFile, $data);
                    }
                    fclose($chunk);
                    @unlink($chunkPath);
                }
                fclose($finalFile);
                @rmdir($tempDir);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to merge chunks', 'details' => $e->getMessage()], 500);
            }

            $mimeType = $this->fileService->getFileMimeTypeByPath($finalPath);

            File::create([
                'user_id'       => $user->id,
                'file_path'     => 'files/' . $finalFileName,
                'file_name'     => $fileName,
                'mime_type'     => $mimeType,
                'file_size'     => $disk->size('files/' . $finalFileName) ?? null,
                'fileable_type' => FolderFactory::class,
                'fileable_id'   => $folderId,
            ]);

            Cache::forget($folderCacheKey);
            Cache::forget($progressCacheKey);

            return response()->json(['message' => 'Upload complete']);
        }

        return response()->json(['message' => 'Chunk uploaded']);
    }


    /* ================================ CHUNK UPLOAD ================================ */
}
